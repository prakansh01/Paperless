<?php include "connection.php";
      include "session.php";
// Fetch live exams where the current time is between reg_start and reg_end
$current_time = date('Y-m-d H:i:s'); // Current date and time
$sql = "SELECT * FROM exams WHERE reg_start <= '$current_time' AND reg_end >= '$current_time'";
$result = $conn->query($sql);
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
                            <button type="button" class="btn btn-outline-light" style="margin-left:10px;"
                                onclick="window.location.href='new_exam.html';">Exams</button>
                        </li>
                    </ul>
                    <button class="btn btn-outline-primary" type="button" style="margin-left:10px;"
                        onclick="window.location.href='logout.php';">Log out</button>
                </div>
            </form>
        </div>
    </nav><br><br><br>










    <div class="container mt-4">
        <h1 class="text-center mb-4">Live Exams</h1>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
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
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo htmlspecialchars($row['num_questions']); ?></td>
                                <td><?php echo htmlspecialchars($row['syllabus']); ?></td>
                                <td><?php echo htmlspecialchars($row['exam_start']); ?></td>
                                <td><?php echo htmlspecialchars($row['marks_correct']); ?></td>
                                <td><?php echo htmlspecialchars($row['marks_incorrect']); ?></td>
                                <td><?php echo htmlspecialchars($row['marks_unattempted']); ?></td>
                                <td><?php echo htmlspecialchars($row['duration']); ?> minutes</td>
                                <td>
                                    <a href="NewForm.php?exam_id=<?php echo urlencode($row['id']); ?>" class="btn btn-primary btn-sm">Apply Now</a>
                                </td>
                            </tr>
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
// Close the database connection
$conn->close();
?>