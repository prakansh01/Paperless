<?php
include "connection.php";

date_default_timezone_set('Asia/Kolkata');

if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    $student_id = htmlspecialchars($student_id, ENT_QUOTES, 'UTF-8');
} else {
    echo "No Roll Number provided in the request.";
    exit;
}


$query = "SELECT * FROM exam_application WHERE roll_no = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid Student ID.");
}

$student = $result->fetch_assoc();
$stmt->close();

$query1 = "SELECT exam_start, exam_end FROM exams WHERE id = ?";
$stmt = $conn->prepare($query1);
$stmt->bind_param("i", $student['exam_id']);
$stmt->execute();
$result = $stmt->get_result();
$exam = $result->fetch_assoc();

// Parse exam start time using strtotime
$current_time = time(); // Current timestamp
$exam_start_time = strtotime($exam['exam_start']); // Convert database time to timestamp
$time_difference = $exam_start_time - $current_time; // Calculate time difference

// Debugging outputs
//echo "Fetched Exam Start Time (from DB): " . $exam['exam_start'] . "<br>";
//echo "Current Time (PHP): " . date('Y-m-d H:i:s', $current_time) . "<br>";
//echo "Exam Start Time: " . date('Y-m-d H:i:s', $exam_start_time) . "<br>";
//echo "Time Difference: " . $time_difference . " seconds<br>";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Countdown</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 400px; font-size: small;">
            <div class="card-body text-center">
                <h5 class="card-title">Exam ID <?php echo $student['exam_id']; ?></h5>
                <img src="<?= htmlspecialchars($student['photo_path']) ?>" alt="Photo" class="img-thumbnail mb-2"
                    style="width: 100px; height: 100px;">
                <p><strong>Name:</strong> <?= htmlspecialchars($student['full_name']) ?></p>
                <p><strong>Roll Number:</strong> <?= htmlspecialchars($student['roll_no']) ?></p>
                <img src="<?= htmlspecialchars($student['signature_path']) ?>" alt="Signature"
                    class="img-thumbnail mb-2" style="width: 100px; height: 50px;">

                <?php if ($time_difference > 0): ?>
                    <h5 class="mt-4">Time Until Exam Starts:</h5>
                    <div id="countdown" class="h5 text-primary"></div>
                <?php else: ?>
                    <p class="text-danger">The exam has already started!</p>
                    <?php header("Location: display.php?student_id={$student_id}&exam_id={$student['exam_id']}");
                    exit; // Always include exit after a header redirect to stop further script execution
                    
                 endif; ?>
            </div>
        </div>
    </div>

    <script>
        <?php if ($time_difference > 0): ?>
            function updateCountdown() {
                // Use the PHP-generated timestamp for the exam start time
                const examStartTime = new Date(<?= json_encode($exam_start_time * 1000) ?>); // Correct PHP to JS conversion
                const now = new Date().getTime();
                const timeLeft = examStartTime - now;

                if (timeLeft > 0) {
                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                    document.getElementById("countdown").innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
                } else {
                    document.getElementById("countdown").innerHTML = "The exam has started!";
                    clearInterval(timerInterval);

                    // Redirect to display.php with student_id and exam_id

                }
            }

            const timerInterval = setInterval(updateCountdown, 1000);
            updateCountdown();
        <?php endif; ?>
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>