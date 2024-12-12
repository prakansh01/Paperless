<?php include "connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $exam_id = $_POST['exam_id'];
    $student_code = $_POST['student_code'];
    $student_roll_no = $_POST['student_roll_no'];
    $dob = $_POST['dob'];
    $alloted_center = $_POST['alloted_center'];

    // Prepare SQL query
    $query = "SELECT * FROM exam_application WHERE exam_id = ? AND student_code = ? AND roll_no = ? AND dob = ? AND alloted_center_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isisi", $exam_id, $student_code, $student_roll_no, $dob, $alloted_center);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['student_code'] = $student_code;
        header("Location: wait.php?id=$student_roll_no");
        exit();
    } else {
        $error_message = "Invalid details. Please try again.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h3 class="text-center mb-4">Exam Login</h3>
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"> <?= $error_message ?> </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="exam_id" class="form-label">Exam ID</label>
                    <input type="text" class="form-control" id="exam_id" name="exam_id" required>
                </div>
                <div class="mb-3">
                    <label for="student_code" class="form-label">Student Code</label>
                    <input type="text" class="form-control" id="student_code" name="student_code" required>
                </div>
                <div class="mb-3">
                    <label for="student_roll_no" class="form-label">Student Roll No.</label>
                    <input type="number" class="form-control" id="student_roll_no" name="student_roll_no" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
                <div class="mb-3">
                    <label for="alloted_center" class="form-label">Alloted Center</label>
                    <input type="text" class="form-control" id="alloted_center" name="alloted_center" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Log In</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
