<?php
require 'connection.php'; // Include database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $actionType = $_POST['action_type'] ?? null;
    $allocationType = $_POST['allocation_type'] ?? null;
    $centerId = $_POST['center_id'] ?? null;
    $examId = $_POST['examid'] ?? null;

    if ($actionType == 'allocate') {
        allocateStudents($allocationType, $centerId, $examId, $conn);
    } elseif ($actionType == 'deallocate') {
        deallocateStudents($allocationType, $centerId, $conn);
    }
}

function allocateStudents($allocationType, $centerId, $examId, $conn) {
    $centerQuery = "SELECT total_systems, available_systems, busy_systems FROM centers WHERE id = ?";
    $stmt = $conn->prepare($centerQuery);
    $stmt->bind_param("i", $centerId);
    $stmt->execute();
    $centerResult = $stmt->get_result()->fetch_assoc();

    if (!$centerResult || $centerResult['available_systems'] <= 0) {
        die("Selected center has no available systems.");
    }

    if ($allocationType == 'one') {
        $rollNo = $_POST['roll_no'];
        allocateStudent($rollNo, $centerId, $examId, $centerResult, $conn);
    } elseif ($allocationType == 'multiple') {
        $startRoll = $_POST['start_roll_no'];
        $endRoll = $_POST['end_roll_no'];
        for ($rollNo = $startRoll; $rollNo <= $endRoll; $rollNo++) {
            allocateStudent($rollNo, $centerId, $examId, $centerResult, $conn);
        }
    }
    echo "Center allocation completed successfully.";
}

function allocateStudent($rollNo, $centerId, $examId, &$centerResult, $conn) {
    if ($centerResult['available_systems'] <= 0) {
        echo "No available systems for student with roll no $rollNo.<br>";
        return;
    }

    $updateStudentQuery = "UPDATE exam_application SET alloted_center_id = ?, exam_id = ? WHERE roll_no = ?";
    $stmt = $conn->prepare($updateStudentQuery);
    $stmt->bind_param("iii", $centerId, $examId, $rollNo);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $centerResult['available_systems']--;
        $centerResult['busy_systems']++;

        $updateCenterQuery = "UPDATE centers SET available_systems = ?, busy_systems = ?, allocated_to = ? WHERE id = ?";
        $stmt = $conn->prepare($updateCenterQuery);
        $stmt->bind_param("iiii", $centerResult['available_systems'], $centerResult['busy_systems'], $examId, $centerId);
        $stmt->execute();

        echo "Allocated student with roll no $rollNo successfully.<br>";
    } else {
        echo "Student with roll no $rollNo not found or already allocated.<br>";
    }
}

function deallocateStudents($allocationType, $centerId, $conn) {
    if ($allocationType == 'one') {
        $rollNo = $_POST['roll_no'];
        deallocateStudent($rollNo, $centerId, $conn);
    } elseif ($allocationType == 'multiple') {
        $startRoll = $_POST['start_roll_no'];
        $endRoll = $_POST['end_roll_no'];
        for ($rollNo = $startRoll; $rollNo <= $endRoll; $rollNo++) {
            deallocateStudent($rollNo, $centerId, $conn);
        }
    }
    echo "Deallocation completed successfully.";
}

function deallocateStudent($rollNo, $centerId, $conn) {
    $resetStudentQuery = "UPDATE exam_application SET alloted_center_id = NULL WHERE roll_no = ?";
    $stmt = $conn->prepare($resetStudentQuery);
    $stmt->bind_param("i", $rollNo);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $updateCenterQuery = "UPDATE centers SET available_systems = available_systems + 1, busy_systems = busy_systems - 1, allocated_to = NULL WHERE id = ?";
        $stmt = $conn->prepare($updateCenterQuery);
        $stmt->bind_param("i", $centerId);
        $stmt->execute();

        echo "Deallocated student with roll no $rollNo successfully.<br>";
    } else {
        echo "Student with roll no $rollNo not found or already deallocated.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Center Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script>
        function toggleFields() {
            const allocationType = document.querySelector("select[name='allocation_type']").value;
            document.getElementById("single-roll-section").style.display = allocationType === 'one' ? 'block' : 'none';
            document.getElementById("multiple-roll-section").style.display = allocationType === 'multiple' ? 'block' : 'none';
        }
    </script>
</head>
<body class="container py-4">
<h2 class="mb-4">Manage Centers</h2>
<form method="POST" class="card p-4">
    <div class="mb-3">
        <label>Select Action:</label>
        <select name="action_type" class="form-select" required>
            <option value="allocate">Allocate</option>
            <option value="deallocate">Deallocate</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Select Allocation Type:</label>
        <select name="allocation_type" class="form-select" required onchange="toggleFields()">
            <option value="one">One Student</option>
            <option value="multiple">Multiple Students</option>
        </select>
    </div>

    <div id="single-roll-section" class="mb-3" style="display:none;">
        <label>Roll Number:</label>
        <input type="number" name="roll_no" class="form-control" min="1">
    </div>

    <div id="multiple-roll-section" class="mb-3" style="display:none;">
        <label>Starting Roll:</label>
        <input type="number" name="start_roll_no" class="form-control" min="1"><br>
        <label>Ending Roll:</label>
        <input type="number" name="end_roll_no" class="form-control" min="1">
    </div>

    <label>Exam ID:</label>
    <input type="number" name="examid" class="form-control" required><br>

    <label>Select Center:</label>
    <select name="center_id" class="form-select" required>
        <?php
        $centerQuery = "SELECT id, name FROM centers WHERE available_systems > 0";
        $result = $conn->query($centerQuery);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select><br>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
</html>