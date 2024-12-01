<?php 
include "connection.php";
include "session.php"; 
?>










<?php
// Fetch exams for the logged in teacher
$teacher_name = $_SESSION['username'];
$exams_sql = "SELECT * FROM exams WHERE teacher_username = ?";
$stmt = $conn->prepare($exams_sql);
$stmt->bind_param("s", $teacher_name);
$stmt->execute();
$exams_result = $stmt->get_result();
?>










<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $_SESSION['username']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        .details {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #2B3035;
            border: 1px solid #ddd;
            border-radius: 10px;
            cursor: pointer;
            transition: box-shadow 0.3s;
        }

        .details:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .container {
            margin-top: 85px;
        }
    </style>
</head>










<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-body fixed-top"
        data-bs-theme="dark">
        <div class="container-fluid">

            <a class="navbar-brand" href="#"><span style="color: green;font-size:23px;">P</span>aperLess.com</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <form>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <button type="button" class="btn btn-outline-light"
                                style="margin-left:10px;">Questions</button>
                        </li>
                    </ul>
                    <button class="btn btn-outline-primary" type="button" style="margin-left:10px;"
                        onclick="window.location.href='logout.php';">Log out</button>
                </div>
            </form>
        </div>
    </nav>










    <!-- Main Content -->
    <div class="container">
        <?php if ($exams_result->num_rows > 0): ?>
            <?php while ($exam = $exams_result->fetch_assoc()): ?>
                <div class="details" data-bs-toggle="collapse" data-bs-target="#questions-<?php echo $exam['id']; ?>">
                    <h3 style="color:#FFFFFF">Exam ID: <?php echo $exam['id']; ?></h3>
                    <table class="table table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Field</th>
                                <th>Details</th>
                                <th>Field</th>
                                <th>Details</th>
                                <th>Field</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Subject Name</td>
                                <td><?php echo $exam['subject']; ?></td>

                                <td>Syllabus</td>
                                <td><?php echo $exam['syllabus']; ?></td>

                                <td>No. of Questions</td>
                                <td><?php echo $exam['num_questions']; ?></td>
                            </tr>
                            <tr>
                                <td>Marks for Correct Answer</td>
                                <td><?php echo $exam['marks_correct']; ?></td>

                                <td>Marks for Incorrect Answer</td>
                                <td><?php echo $exam['marks_incorrect']; ?></td>

                                <td>Marks for Unattempted</td>
                                <td><?php echo $exam['marks_unattempted']; ?></td>
                            </tr>
                            <tr>
                                <td>Duration Of Exam</td>
                                <td><?php echo $exam['duration']; ?></td>

                                <td>Starting of Exam</td>
                                <td><?php echo $exam['exam_start']; ?></td>

                                <td>Ending of Exam</td>
                                <td><?php echo $exam['exam_end']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div id="questions-<?php echo $exam['id']; ?>" class="collapse">
                    <div class="card card-body mt-3">
                        <h6>Questions for Exam ID <?php echo $exam['id']; ?>:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Correct Answer</th>
                                    <th>Option 1</th>
                                    <th>Option 2</th>
                                    <th>Option 3</th>
                                    <th colspan="2"><a class="btn btn-success" role="button" href="addquestion.php?id=<?php echo urlencode($exam['id']); ?>">Add Q</a>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $questions_sql = "SELECT * FROM questions WHERE exam_id = " . $exam['id'];
                                $questions_result = $conn->query($questions_sql);
                                if ($questions_result->num_rows > 0):
                                    while ($question = $questions_result->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td><?php echo $question['question_id']; ?></td>
                                            <td><?php echo $question['question']; ?></td>
                                            <td><?php echo $question['correct_answer']; ?></td>
                                            <td><?php echo $question['option1']; ?></td>
                                            <td><?php echo $question['option2']; ?></td>
                                            <td><?php echo $question['option3']; ?></td>
                                            <td><a class="btn btn-primary" role="button" href="editquestion.php?id=<?php echo urlencode($question['question_id']); ?>">Edit</a></td>
                                            <td><a class="btn btn-danger" role="button" href="deletequestion.php?id=<?php echo urlencode($question['question_id']); ?>">Delete</a></td>












                                        </tr>
                                        <?php
                                    endwhile;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="8">No questions found for this exam.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No exams found!</div>
        <?php endif; ?>
    </div>











    <div style="height:1200px;" class="free-space"></div>










    <footer style="margin-top:0px;">
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













</body>

</html>










<?php
$conn->close();
?>