<?php
// Include database connection
include 'connection.php';

// Get the student ID and exam ID from the URL or session
$student_id = $_GET['student_id']; // or use session to get student ID
$exam_id = $_GET['exam_id']; // or use session to get exam ID



// Fetch exam details
$exam_query = "SELECT subject, num_questions,  marks_correct, marks_incorrect, marks_unattempted FROM exams WHERE id = ?";
$stmt = $conn->prepare($exam_query);
$stmt->bind_param("i", $exam_id);
$stmt->execute();
$exam_result = $stmt->get_result();
$exam = $exam_result->fetch_assoc();



// Fetch student details
$student_query = "SELECT full_name, father_name, mother_name, dob, email, photo_path FROM exam_application WHERE exam_id = ? AND roll_no = ?";
$stmt = $conn->prepare($student_query);
$stmt->bind_param("is", $exam_id, $student_id);
$stmt->execute();
$student_result = $stmt->get_result();
$student = $student_result->fetch_assoc();



// Fetch the questions and responses for the exam
$questions_query = "
    SELECT q.question_id, q.question_text, q.option_a, q.option_b, q.option_c, q.option_d, 
           q.correct_option, sr.selected_option, sr.response_status
    FROM questions q
    LEFT JOIN student_responses sr ON q.question_id = sr.question_id AND sr.student_id = ?
    WHERE q.exam_id = ?";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param("ii", $student_id, $exam_id);
$stmt->execute();
$questions_result = $stmt->get_result();



// Initialize the marks counters
$total_marks = 0;
$correct_marks = 0;
$incorrect_marks = 0;
$unattempted_marks = 0;
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        .correct-answer {
            background-color: green;
            color: white;
        }

        .selected-answer {
            background-color: blue;
            color: white;
        }

        .unattempted-answer {
            background-color: lightgray;
        }

        .question-container {
            margin-bottom: 30px;
        }

        .question-text {
            font-weight: bold;
        }

        .result-card {
            margin-top: 30px;
        }

        .result-summary {
            font-size: 1.2em;
        }

        .profile-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
        }

        .animation-fade {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-light">




    <div class="container mt-5 animation-fade">
        <!-- Display Exam Name and Marks Description -->
        <div class="text-center border-dark px-2">
            <h2 class="mb-3"><?php echo $exam['subject']; ?> - Result</h2>
        </div>

        <!-- Display Student Information -->
        <div class="d-flex justify-content-around align-items-center">
            <img src="<?php echo $student['photo_path']; ?>" alt="Student Photo" class="profile-img">
            <div>
                <h4><?php echo $student['full_name']; ?></h4>
                <p><strong>Father:</strong> <?php echo $student['father_name']; ?></p>
                <p><strong>Mother:</strong> <?php echo $student['mother_name']; ?></p>
                <p><strong>Date of Birth:</strong> <?php echo $student['dob']; ?></p>
                <p><strong>Email:</strong> <?php echo $student['email']; ?></p>
            </div>
        </div>


























        <!-- Display Questions and Responses -->


        <div class="mt-4 py-4 border border-dark px-4">
            <div class="text-center py-4 mb-4 border border-dark">
                <pre class="lead">Marks per correct answer: <?php echo $exam['marks_correct']; ?>    Marks for incorrect answer: <?php echo $exam['marks_incorrect']; ?>     Marks for unattempted question: <?php echo $exam['marks_unattempted']; ?></pre><br>
            </div>
            <?php
            while ($question = $questions_result->fetch_assoc()) {
                $correct_option = $question['correct_option'];
                $selected_option = $question['selected_option'];
                $response_status = $question['response_status'];

                // Calculate the marks based on the selected answer
                if ($response_status == 'solved' || $response_status == 'doubt') {
                    if ($selected_option == $correct_option) {
                        $total_marks += $exam['marks_correct'];
                        $correct_marks += $exam['marks_correct'];
                    } else {
                        $total_marks += $exam['marks_incorrect'];
                        $incorrect_marks += $exam['marks_incorrect'];
                    }
                } else {
                    $total_marks += $exam['marks_unattempted'];
                    $unattempted_marks += $exam['marks_unattempted'];
                }
                ?>
                <div class="question-container">
                    <p class="question-text"><?php echo $question['question_text']; ?></p>
                    <div class="row">
                        <?php
                        $options = ['A' => $question['option_a'], 'B' => $question['option_b'], 'C' => $question['option_c'], 'D' => $question['option_d']];
                        foreach ($options as $option_key => $option_value) {
                            $is_correct = ($option_key == $correct_option) ? 'correct-answer' : '';
                            $is_selected = ($option_key == $selected_option) ? 'selected-answer' : '';
                            $is_unattempted = (!$selected_option && $response_status != 'solved') ? 'unattempted-answer' : '';
                            ?>
                            <div class="col-md-3 mb-2">
                                <button
                                    class="btn btn-block <?php echo $is_correct . ' ' . $is_selected . ' ' . $is_unattempted; ?>"><?php echo $option_value; ?></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>



































































        <!-- Display Final Results -->
        <div class="result-card bg-white p-4 rounded shadow my-4">
            <h4 class="text-center">Final Results :
                <?php echo $total_marks . "/" . ($exam['num_questions'] * $exam['marks_correct']); ?>
            </h4>
            <div class="result-summary d-flex justify-content-around py-4">
                <p><strong>Total Marks:</strong> <?php echo $total_marks; ?></p>
                <p><strong>Marks for Correct:</strong> <?php echo $correct_marks; ?></p>
                <p><strong>Marks for Incorrect:</strong> <?php echo $incorrect_marks; ?></p>
                <p><strong>Marks for Unattempted:</strong> <?php echo $unattempted_marks; ?></p>
            </div>
        </div>
    </div>





































    <footer style="margin-top:200px;">
        <div class="footer-content">
            <div>
                <h4>Contact Us</h4>
                <ul>
                    <li><a href="#">Email: support@paperless.com</a></li>
                    <li><a href="#">Phone: +91 94510 31243</a></li>
                </ul>
            </div>
            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                    <li><a href="#">FAQ</a></li>
                </ul>
            </div>
            <div>
                <h4>Follow Us</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <p>© 2024 Online Examination Management System. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>