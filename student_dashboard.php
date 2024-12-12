<?php include "connection.php";








// Fetch live exams where the current time is between reg_start and reg_end
$current_time = date('Y-m-d H:i:s'); // Current date and time
$sql = "SELECT * FROM exams WHERE reg_start <= '$current_time' AND reg_end >= '$current_time'";
$exams_result = $conn->query($sql);
?>










<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $_SESSION['username']; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        .compact-table {
            font-size: 0.85rem;
            /* Small font size for compact display */
        }

        .table-container {
            margin-bottom: 20px;
            /* Space between tables */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            border-radius: 8px;
            /* Rounded corners */
        }
    </style>
        <script type="text/javascript">
        function changeButtonContent(content, href) {
            // JavaScript function to change button content and href
            var button = document.getElementById('myButton');
            button.innerHTML = content;
            button.href = href;
        }
    </script>
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
    </nav><br><br><br>










    <div class="container mt-4">
        <h1 class="text-center mb-4">Live Exams <?php

        ?></h1>

        <?php if ($exams_result->num_rows > 0): ?>
            <?php while ($exam = $exams_result->fetch_assoc()): ?>
                <div class="table-container table-responsive">
                    <table class="table table-bordered table-striped compact-table">
                        <thead class="table-light">
                            <tr>
                                <th width="12%">Subject</th>
                                <th width="13%">Number of Questions</th>
                                <th width="27%">Syllabus</th>
                                <th width="15%">Date</th>
                                <th width="2.5%"><i class="fas fa-check-circle" style="color: green;"></i></th>
                                <th width="2.5%"><i class="fas fa-times-circle" style="color: red;"></i></th>
                                <th width="2.5%"><i class="fas fa-question-circle" style="color: grey;"></i></th>
                                <th width="11%">Duration</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo htmlspecialchars($exam['subject']); ?></td>
                                <td><?php echo htmlspecialchars($exam['num_questions']); ?></td>
                                <td><?php echo htmlspecialchars($exam['syllabus']); ?></td>
                                <td><?php echo htmlspecialchars($exam['exam_start']); ?></td>
                                <td><?php echo htmlspecialchars($exam['marks_correct']); ?></td>
                                <td><?php echo htmlspecialchars($exam['marks_incorrect']); ?></td>
                                <td><?php echo htmlspecialchars($exam['marks_unattempted']); ?></td>
                                <td><?php echo htmlspecialchars($exam['duration']); ?> minutes</td>
                                <td>
                                    <a id="myButton" href="NewForm.php?exam_id=<?php echo urlencode($exam['id']); ?>"
                                        class="btn btn-primary btn-sm">Apply Now</a>
                                </td>
                            </tr>
                            <?php
                            $query1 = "SELECT * FROM exam_application WHERE user_id = ? and exam_id = ?";
                            $stmt1 = $conn->prepare($query1);
                            $stmt1->bind_param("ii", $_SESSION['user_id'], $exam['id']);
                            $stmt1->execute();
                            $registration_result = $stmt1->get_result();
                            $admit = $registration_result->fetch_assoc();

                            if ($registration_result->num_rows > 0) {
                                ?>
                                <tr>
                                    <strong>
                                        <td class="alert alert-warning text-success text-center" colspan="8">You have successfully
                                            registered for this exam</td>
                                    </strong>


                                    <?php
                                    $admit_card_issue = strtotime($exam['admit_card_issue']);
                                    $admit_card_expire = strtotime($exam['admit_card_expire']);
                                    $result_issue = strtotime($exam['result_issue']);
                                    $result_expire = strtotime($exam['result_expire']);

                                    $current_time = time();
                                    // Check if the current time is within the admit card issue and expire period
                                    if ($current_time >= $admit_card_issue && $current_time <= $admit_card_expire && $admit['alloted_center_id'] != null) {

                                        echo "<script>changeButtonContent('Admit Card','admit_card.php?id=". urlencode($_SESSION['user_id'])."&exam_id=".$exam['id']."');</script>";

                                    } else {
                                        ?>
                                        <td class="text-center text-info alert alert-warning">Admit card Not available</td>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    $query = "SELECT roll_no FROM exam_application WHERE user_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $_SESSION['user_id']); // "i" is for integer type
                                    $stmt->execute();
                                    $stmt->bind_result($roll_no);
                                    $stmt->fetch();
                                    $stmt->close();

                                    $query = "SELECT * FROM student_responses WHERE student_id = ?";
                                    $stmt = $conn->prepare($query);
                                    $stmt->bind_param("i", $roll_no); // "i" is for integer type
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $stmt->close();
                                    if ($result->num_rows > 0) {
                                        $std = $result->fetch_assoc();
                                        // Student responses are saved in the db , means this student has given the exam
                                        if ($current_time >= $result_issue && $current_time <= $result_expire) {
                                            echo "<script>changeButtonContent('See Result','result.php?student_id=". urlencode($std['student_id']) ."&exam_id=". urlencode($std['exam_id'])."');</script>";
                                        }
                                    }
                                    ?>
                                </tr>
                                <?php

                            } else {
                                ?>
                                <tr>
                                    <td class="alert alert-warning text-danger text-center" colspan="9">You have not yet registered
                                        for this exam</td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-info text-center">
                <strong>No live exams available right now.</strong>
            </div>
        <?php endif; ?>

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










</body>

</html>
<?php
// Close the database connection
$conn->close();
?>