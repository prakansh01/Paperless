<?php include "connection.php";
$id = isset($_GET['id']) ? $_GET['id'] : null;
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : null;
$query = "SELECT * FROM exam_application WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();
?>



<?php
$query1 = "SELECT * FROM exams WHERE id = ?";
$stmt1 = $conn->prepare($query1);
$stmt1->bind_param("i", $exam_id);
$stmt1->execute();
$result1 = $stmt1->get_result();
$exam = $result1->fetch_assoc();
$stmt1->close();
?>



<?php
$center_id = $student['alloted_center_id'];
$query = "SELECT name, address FROM centers WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $center_id);
$stmt->execute();
$stmt->bind_result($name, $address);
$stmt->fetch();
$conn->close();
?>



<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>admit Card</title>
    <style>
        .txt-center {
            text-align: center;
        }

        .border- {
            border: 1px solid #000 !important;
        }

        .padding {
            padding: 15px;
        }

        .mar-bot {
            margin-bottom: 15px;
        }

        .admit-card {
            border: 2px solid #000;
            padding: 15px;
            margin: 20px 0;
        }

        .BoxA h5,
        .BoxA p {
            margin: 0;
        }

        h5 {
            text-transform: uppercase;
        }

        table img {
            width: 100%;
            margin: 0 auto;
        }

        .table-bordered td,
        .table-bordered th,
        .table thead th {
            border: 1px solid #000000 !important;
        }

        /* Scale the entire webpage content */
        .scaled-content {
            transform: scale(0.80);
            /* Scale to 65% */
            transform-origin: top center;
            /* Scale from the top-left corner */
        }

        button{
            position: relative;
            bottom: 140px;;
        }
    </style>
</head>

<body>
    <div class="scaled-content" id="admitCard">
        <section>
            <div class="container">
                <div class="admit-card">

                    <div class="BoxC border- padding mar-bot">
                        <div class="row">
                            <div class="col-12 d-flex">
                                <h5>Roll No : <?php echo $student['roll_no']; ?></h5>
                                <h5 style="margin-left:120px;">Exam Id : <?php echo $exam_id; ?></h5>
                                <h5 style="margin-left:120px;">Student Code : <?php echo $student['student_code']; ?>
                                </h5>
                                <h5 style="margin-left:120px;">Center Id : <?php echo $student['alloted_center_id']; ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="BoxD border- padding mar-bot">
                        <div class="row">
                            <div class="col-sm-10">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><b>Candidate Name : <?php echo $student['full_name']; ?></b></td>
                                            <td><b>Gender : </b><?php echo $student['gender']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Father Name: </b><?php echo $student['father_name']; ?></td>
                                            <td><b>Category: </b><?php echo $student['category']; ?></td>
                                        </tr>
                                        <tr>
                                            <td><b>Mother Name: </b><?php echo $student['mother_name']; ?></td>
                                            <td><b>DOB: </b><?php echo $student['dob']; ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" style="    height: 88px;"><b>Address:
                                                </b><?php echo $student['address']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-sm-2 txt-center">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row txt-center"><img src=" <?php echo $student['photo_path'] ?> "
                                                    width="123px" /></th>
                                        </tr>
                                        <tr>
                                            <th scope="row txt-center"><img
                                                    src=" <?php echo $student['signature_path'] ?> " /></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="BoxE border- padding mar-bot txt-center">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>EXAMINATION VENUE</h5>
                                <p><?php echo htmlspecialchars($name); ?> <br><?php echo htmlspecialchars($address); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="BoxF border- padding mar-bot txt-center">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Subject/Paper</th>
                                            <th>Syllabus</th>
                                            <th>Exam Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td><?php echo $exam['subject']; ?></td>
                                            <td><?php echo $exam['syllabus']; ?></td>
                                            <td><?php echo $exam['exam_start']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <div class="text-center">
        <button class="btn btn-primary" onclick="downloadPDF()">Download as PDF</button>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('admitCard');
            html2pdf()
                .from(element)
                .save('Admit_Card.pdf');
        }
    </script>
</body>

</html>