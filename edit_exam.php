<?php include "connection.php";

// Check if 'id' is provided via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $exam_id = intval($_GET['id']); // Sanitize the input

    // Handle form submission for editing
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $subject = $_POST['subject'];
        $num_questions = intval($_POST['num_questions']);
        $marks_correct = floatval($_POST['marks_correct']);
        $marks_incorrect = floatval($_POST['marks_incorrect']);
        $marks_unattempted = floatval($_POST['marks_unattempted']);
        $duration = intval($_POST['duration']);
        $reg_start = $_POST['reg_start'];
        $reg_end = $_POST['reg_end'];
        $admit_card_issue = $_POST['admit_card_issue'];
        $admit_card_expire = $_POST['admit_card_expire'];
        $exam_start = $_POST['exam_start'];
        $exam_end = $_POST['exam_end'];
        $teacher_username = $_POST['teacher_username'];
        $venue = $_POST['venue'];
        $student_code = $_POST['student_code'];
        $max_students = intval($_POST['max_students']);
        $syllabus = $_POST['syllabus'];
        $result_issue = $_POST['result_issue'];
        $result_expire = $_POST['result_expire'];

        // Update query
        $sql = "UPDATE exams SET
                    subject = ?, 
                    num_questions = ?, 
                    marks_correct = ?, 
                    marks_incorrect = ?, 
                    marks_unattempted = ?, 
                    duration = ?, 
                    reg_start = ?, 
                    reg_end = ?, 
                    admit_card_issue = ?, 
                    admit_card_expire = ?, 
                    exam_start = ?, 
                    exam_end = ?, 
                    teacher_username = ?,  
                    venue = ?, 
                    student_code = ?, 
                    max_students = ?, 
                    syllabus = ?, 
                    result_issue = ?, 
                    result_expire = ?
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param(
                "sidddisssssssssissss",
                $subject,
                $num_questions,
                $marks_correct,
                $marks_incorrect,
                $marks_unattempted,
                $duration,
                $reg_start,
                $reg_end,
                $admit_card_issue,
                $admit_card_expire,
                $exam_start,
                $exam_end,
                $teacher_username,
                $venue,
                $student_code,
                $max_students,
                $syllabus,
                $result_issue,
                $result_expire,
                $exam_id
            );

            if ($stmt->execute()) {
                header("Location: admin_dashboard.php?message=exam_updated");
                exit();
            } else {
                echo "Error updating exam: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing the update query: " . $conn->error;
        }
    }

    // Fetch existing exam details for the form
    $sql = "SELECT * FROM exams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $exam_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exam = $result->fetch_assoc();
        $stmt->close();
    } else {
        die("Error preparing the select query: " . $conn->error);
    }
} else {
    die("No exam ID provided.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Exam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body style="font-size:.7rem;">
    <div class="container my-5">
        <form method="POST">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>Field</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Subject</strong></td>
                            <td><input type="text" name="subject"
                                    value="<?php echo htmlspecialchars($exam['subject']); ?>" required></td>
                        </tr>
                        <tr>
                            <td><strong>Number of Questions</strong></td>
                            <td><input type="number" name="num_questions" value="<?php echo $exam['num_questions']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Marks for Correct Answer</strong></td>
                            <td><input type="text" name="marks_correct" value="<?php echo $exam['marks_correct']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Marks for Incorrect Answer</strong></td>
                            <td><input type="text" name="marks_incorrect"
                                    value="<?php echo $exam['marks_incorrect']; ?>" required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Marks for Unattempted</strong></td>
                            <td><input type="text" name="marks_unattempted"
                                    value="<?php echo $exam['marks_unattempted']; ?>" required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Duration</strong></td>
                            <td><input type="number" name="duration" value="<?php echo $exam['duration']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Registration Start</strong></td>
                            <td><input type="datetime-local" name="reg_start"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['reg_start'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Registration End</strong></td>
                            <td><input type="datetime-local" name="reg_end"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['reg_end'])); ?>" required><br>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Admit Card Issue</strong></td>
                            <td><input type="datetime-local" name="admit_card_issue"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['admit_card_issue'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Admit Card Expire</strong></td>
                            <td><input type="datetime-local" name="admit_card_expire"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['admit_card_expire'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Exam Start</strong></td>
                            <td><input type="datetime-local" name="exam_start"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['exam_start'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Exam End</strong></td>
                            <td><input type="datetime-local" name="exam_end"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['exam_end'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Teacher Username</strong></td>
                            <td><input type="text" name="teacher_username"
                                    value="<?php echo $exam['teacher_username']; ?>" required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Venue</strong></td>
                            <td><input type="text" name="venue" value="<?php echo $exam['venue']; ?>" required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Student Code</strong></td>
                            <td><input type="text" name="student_code" value="<?php echo $exam['student_code']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Maximum Students</strong></td>
                            <td><input type="text" name="max_students" value="<?php echo $exam['max_students']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Syllabus</strong></td>
                            <td><input type="text" name="syllabus" value="<?php echo $exam['syllabus']; ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Result Issue</strong></td>
                            <td><input type="datetime-local" name="result_issue"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['result_issue'])); ?>"
                                    required><br></td>
                        </tr>
                        <tr>
                            <td><strong>Result Expire</strong></td>
                            <td><input type="datetime-local" name="result_expire"
                                    value="<?php echo date('Y-m-d\TH:i', strtotime($exam['result_expire'])); ?>"
                                    required><br></td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" value="Update Exam">
            </div>
        </form>
    </div>
</body>

</html>