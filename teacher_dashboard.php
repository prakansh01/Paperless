<?php
include "connection.php";
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


<?PHP
// Handle form submissions for adding, editing, and deleting questions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_question'])) {
        // Add a new question
        $exam_id = intval($_POST['exam_id']);
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $option_a = $conn->real_escape_string($_POST['option_a']);
        $option_b = $conn->real_escape_string($_POST['option_b']);
        $option_c = $conn->real_escape_string($_POST['option_c']);
        $option_d = $conn->real_escape_string($_POST['option_d']);
        $correct_option = $conn->real_escape_string($_POST['correct_option']);

        $conn->query("INSERT INTO questions (exam_id, question_text, option_a, option_b, option_c, option_d, correct_option) 
                      VALUES ('$exam_id', '$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')");
    } elseif (isset($_POST['edit_question'])) {
        // Edit an existing question
        $question_id = intval($_POST['question_id']);
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $option_a = $conn->real_escape_string($_POST['option_a']);
        $option_b = $conn->real_escape_string($_POST['option_b']);
        $option_c = $conn->real_escape_string($_POST['option_c']);
        $option_d = $conn->real_escape_string($_POST['option_d']);
        $correct_option = $conn->real_escape_string($_POST['correct_option']);

        $conn->query("UPDATE questions SET question_text = '$question_text', option_a = '$option_a', option_b = '$option_b', 
                      option_c = '$option_c', option_d = '$option_d', correct_option = '$correct_option' 
                      WHERE question_id = $question_id");
    } elseif (isset($_POST['delete_question'])) {
        // Delete a question
        $question_id = intval($_POST['question_id']);
        $conn->query("DELETE FROM questions WHERE question_id = $question_id");
    }
}

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
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Question</th>
                                    <th>Correct Answer</th>
                                    <th>Option 1</th>
                                    <th>Option 2</th>
                                    <th>Option 3</th>
                                    <th>Correct</th>
                                    <th colspan="2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $questions = $conn->query("SELECT * FROM questions WHERE exam_id = " . intval($exam['id']));
                                ?>

                                <?php if ($questions->num_rows > 0): ?>
                                    <?php while ($question = $questions->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= $question['question_id'] ?></td>
                                            <form method="POST" class="d-flex">
                                                <input type="hidden" name="question_id" value="<?= $question['question_id'] ?>">
                                                <td style="width:400px;"><textarea style="height:30px;" name="question_text"
                                                        class="form-control"
                                                        required><?= htmlspecialchars($question['question_text']) ?></textarea></td>
                                                <td><input type="text" name="option_a" class="form-control"
                                                        value="<?= htmlspecialchars($question['option_a']) ?>" required></td>
                                                <td><input type="text" name="option_b" class="form-control"
                                                        value="<?= htmlspecialchars($question['option_b']) ?>" required></td>
                                                <td><input type="text" name="option_c" class="form-control"
                                                        value="<?= htmlspecialchars($question['option_c']) ?>" required></td>
                                                <td><input type="text" name="option_d" class="form-control"
                                                        value="<?= htmlspecialchars($question['option_d']) ?>" required></td>
                                                <td style="width:20px;">
                                                    <select name="correct_option" class="form-control text-center" required>
                                                        <option value="A" <?= $question['correct_option'] == 'A' ? 'selected' : '' ?>>A
                                                        </option>
                                                        <option value="B" <?= $question['correct_option'] == 'B' ? 'selected' : '' ?>>B
                                                        </option>
                                                        <option value="C" <?= $question['correct_option'] == 'C' ? 'selected' : '' ?>>C
                                                        </option>
                                                        <option value="D" <?= $question['correct_option'] == 'D' ? 'selected' : '' ?>>D
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <button type="submit" name="edit_question"
                                                        class="btn btn-primary btn-sm">Edit</button>
                                                </td>
                                            </form>
                                            <form method="POST">
                                                <input type="hidden" name="question_id" value="<?= $question['question_id'] ?>">
                                                <td><button type="submit" name="delete_question"
                                                        class="btn btn-danger btn-sm">Delete</button></td>
                                            </form>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">No questions found. Add a new question below.</p>
                                <?php endif; ?>

                                <tr>
                                    <form method="POST" action="">
                                        <td ><input type="hidden" id="exam_id" name="exam_id" class="form-control" value="<?php echo $exam['id'] ?>" required></td>
                                        <td style="width:400px;"><textarea  style="height:30px;" id="question_text" name="question_text"
                                                class="form-control" required></textarea></td>
                                        <td><input type="text" id="option_a" name="option_a" class="form-control text-center" required></td>
                                        <td><input type="text" id="option_b" name="option_b" class="form-control text-center" required></td>
                                        <td><input type="text" id="option_c" name="option_c" class="form-control text-center" required></td>
                                        <td><input type="text" id="option_d" name="option_d" class="form-control text-center" required></td>
                                        <td style="width:20px;">
                                            <select id="correct_option" name="correct_option" class="form-control text-center" required>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                            </select>
                                        </td>
                                        <td colspan="3"><button type="submit" name="add_question"
                                                class="btn btn-success">Add</button></td>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">No exams found!</div>
        <?php endif; ?>
    </div>


    <footer style="margin-top:200px;">
        <div class="footer-content ">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>