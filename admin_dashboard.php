<?php include "connection.php"; ?>







<?php
// Fetch exams for the logged in admin
$admin_name = $_SESSION['username'];
$exams_sql = "SELECT * FROM exams WHERE admin_id = ?";
$stmt = $conn->prepare($exams_sql);
$stmt->bind_param("s", $admin_name);
$stmt->execute();
$exams_result = $stmt->get_result();
?>







<?php
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $exam_id = intval($_GET['id']);
    $sql = "DELETE FROM exams WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $exam_id);
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=exam_deleted");
            exit();
        } else {
            echo "Error deleting exam: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "No exam ID provided or invalid request.";
}
?>







<?php
// Check if student 'id' is provided via GET
if (isset($_GET['del_id']) && !empty($_GET['del_id'])) {
    $form_id = intval($_GET['del_id']); // Sanitize the input
    $sql = "DELETE FROM exam_application WHERE roll_no = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $form_id);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=delete_student");
            exit();
        } else {
            echo "Error updating exam: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "No exam ID provided or invalid request.";
}
?>








<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome <?php echo $_SESSION['username']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet" />
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

        table {
            font-size: 0.70rem;
            /* Small responsive font size */
            text-align: center;
            vertical-align: middle;
        }

        .student-photo {
            width: 50px;
            height: 50px;
            transition: transform 0.2s ease;
        }

        .student-photo:hover {
            transform: scale(3);
            /* Enlarges the image 4x on hover */
        }

        .options {
            display: flex;
            flex-direction: row;
            align-items: center;
        }

        #delete_btn {
            margin-left: 50px;
        }

        #edit_btn {
            margin-left: 15px;
        }

        #centerModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            z-index: 1050;
        }

        #centerContainer {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

    </style>
</head>

<body>




    <!-- Display success message if redirected from new_exam.php after successful creation of exam-->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:65px;">
            New Exam has been Created Successfully
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>




    <!-- Display message if redirected from delete_exam.php after successful exam deletion -->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'exam_deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:65px;">
            Exam has been successfully deleted
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>





    <!-- Display message if redirected from edit_exam.php after successful exam updation -->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'exam_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:65px;">
            Exam has been successfully Updated
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>






    <!-- Display message if redirected from delete_student.php after successful student_deletion -->
    <?php if (isset($_GET['message']) && $_GET['message'] == 'delete_student'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="margin-top:65px;">
            Student has been successfully deleted from the exam
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>




    <?php //fetch id of admin from the users table
    $query3 = "SELECT * FROM users WHERE username = ?";
    $stmt3 = $conn->prepare($query3);
    $stmt3->bind_param("s", $admin_name);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $user = $result3->fetch_assoc();
    // echo$user['id'];
    ?>






    <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark border-bottom border-body fixed-top"
        data-bs-theme="dark">
        <div class="container-fluid" style="display:flex;align-items:center;">

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
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-light dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Admin Controls
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="centers.php">Manage Centers</a>
                                    </li>
                                    <li><a class="dropdown-item" href="centerAllotment.php">Center Allotment</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-outline-light" style="margin-left:10px;"
                                onclick="window.location.href='new_exam.html'">New Exam</button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="btn btn-outline-light" style="margin-left:10px;">Post
                                Exams</button>
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
                <div class="details" data-bs-toggle="collapse" data-bs-target="#students-<?php echo $exam['id']; ?>">
                    <div class="options" >
                        <h3 style="color:#FFFFFF">Exam ID: <?php echo $exam['id']; ?></h3>
                        <a id="delete_btn" role="button"
                            onclick="return confirm('Are you sure , you want to delete this exam ?')"
                            href="admin_dashboard.php?id=<?php echo $exam['id']; ?>"><i style="color: white; font-size: 18px;"
                                class="fas fa-trash-alt text-white fs-5 link-danger"></i></a>
                        <a id="edit_btn" role="button" href="edit_exam.php?id=<?php echo $exam['id'] ?>"><i
                                class="fas fa-edit fa-thin text-white fs-5 link-warning"
                                style="color: white; font-size: 18px;"></i></a>

                    </div>
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

                <div id="students-<?php echo $exam['id']; ?>" class="collapse">
                    <div class="card card-body mt-3">

                        <?php
                        $examId = $exam['id'];
                        $studentData = null;
                        if ($examId) {
                            $details_sql = "SELECT * FROM exam_application WHERE exam_id = " . $examId;
                            $details_result = $conn->query($details_sql);

                            if ($details_result->num_rows > 0) {
                                while ($details = $details_result->fetch_assoc()) {
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-dark">
                                                <tr>
                                                    <th>Roll No</th>
                                                    <th rowspan="2">Photo</th>
                                                    <th colspan="8">Student Information</th>
                                                    <th width="4%">
                                                        <a role="button"
                                                            onclick="return confirm('Are you sure , you want to delete this Student Form ?')"
                                                            href="admin_dashboard.php?del_id=<?php echo $details['roll_no']; ?>"><i
                                                                style="color: white; font-size: 18px;"
                                                                class="fas fa-trash-alt text-white fs-5 link-danger"></i></a>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td> <?php echo $details['roll_no']; ?> </td>
                                                    <td rowspan="2">
                                                        <div class="img-container">
                                                            <?php $photo_path = htmlspecialchars($details['photo_path']); ?>
                                                            <img class="student-photo rounded" src=" <?php echo $photo_path ?> "
                                                                alt="Student Photo">
                                                        </div>
                                                    </td>
                                                    <td><strong>Name : </strong><?= htmlspecialchars($details['full_name']) ?></td>
                                                    <td><strong>Mother : </strong> <?= htmlspecialchars($details['mother_name']) ?></td>
                                                    <td><strong>Father : </strong> <?= htmlspecialchars($details['father_name']) ?></td>
                                                    <td><?= htmlspecialchars($details['gender']) ?></td>
                                                    <td><?= htmlspecialchars($details['dob']) ?></td>
                                                    <td><?= htmlspecialchars($details['category']) ?></td>
                                                    <td><?= htmlspecialchars($details['address']) ?></td>
                                                    <td><?= htmlspecialchars($details['state']) ?></td>
                                                    <td><?= htmlspecialchars($details['city']) ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?php
                                                        if ($details['alloted_center_id'] == null)
                                                            echo '<i class="fas fa-times-circle" style="color: red;"></i>';
                                                        else if ($details['alloted_center_id'] != null)
                                                            echo '<i class="fas fa-check-circle" style="color: green;"></i>';
                                                        ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($details['email']) ?></td>
                                                    <td><strong>Phone : </strong> <?= htmlspecialchars($details['phone']) ?></td>
                                                    <td><strong>Whatsapp : </strong> <?= htmlspecialchars($details['whatsapp']) ?></td>
                                                    <td><strong>10th : </strong> <?= htmlspecialchars($details['tenth_percentage']) ?>%</td>
                                                    <td><strong>12th </strong> <?= htmlspecialchars($details['twelfth_percentage']) ?>%</td>
                                                    <td><?= htmlspecialchars($details['stream']) ?></td>
                                                    <td><?= htmlspecialchars($details['hobby']) ?></td>
                                                    <td><?= htmlspecialchars($details['competition']) ?></td>
                                                    <td><strong>Rank : </strong> <?= htmlspecialchars($details['rank']) ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <?php
                                }
                            } else {
                                ?>
                                <tr>
                                    <td class="alert alert-warning text-center" colspan="8">No Students found for this exam.</td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td class="alert alert-warning text-center" colspan="8">No Exam is Found </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </div>
                </div>


            <?php endwhile; ?>
        <?php endif; ?>
    </div>






    <!-- Center Add Form -->
    <div id="centerModal">
        <div id="centerContainer" class="container">
            <h3 class="text-center">Add New Center</h3>
            <form action="create_center.php" method="POST">
                <input type="hidden" value=<?php echo $user['id']; ?> name="admin_id">
                <div class="mb-3">
                    <label for="centerName" class="form-label">Center Name</label>
                    <input type="text" class="form-control" id="centerName" name="centerName"
                        placeholder="Enter center name" required>
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Center Address</label>
                    <input type="address" class="form-control" id="address" name="address"
                        placeholder="Enter center address" required>
                </div>
                <div class="mb-3">
                    <label for="computers" class="form-label">Available Systems</label>
                    <input type="number" class="form-control" id="computers" name="computers"
                        placeholder="Enter the number of available computers" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-danger" id="cancelButton">Cancel</button>
                </div>
            </form>
        </div>
    </div>






    <div style="height:1200px;" class="free-space"></div>





    <!-- Footer -->
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



    <script>
        const AddCenter = document.getElementById("AddCenter");
        const centerModal = document.getElementById("centerModal");
        const cancelButton = document.getElementById("cancelButton");

        AddCenter.addEventListener("click", () => {
            centerModal.style.display = "block";
        });

        cancelButton.addEventListener("click", () => {
            centerModal.style.display = "none";
        });
    </script>

</body>

</html>


<?php
$conn->close();
?>