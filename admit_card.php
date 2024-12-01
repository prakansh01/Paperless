<?php
// Database connection
$host = 'localhost';
$dbname = 'paperless_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Fetch student details
$student_id = 4; // Replace with dynamic student ID logic
$query1 = "SELECT * FROM exam_application WHERE id = :student_id";
$stmt1 = $pdo->prepare($query1);
$stmt1->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt1->execute();
$student = $stmt1->fetch(PDO::FETCH_ASSOC);



// Fetch exam details
$exam_id = 4;
$query2 = "SELECT * FROM exams WHERE id = :exam_id";
$stmt2 = $pdo->prepare($query2);
$stmt2->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$stmt2->execute();
$exams = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        .admit-card {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            border: 1px solid #ddd;
            max-width: 800px;
        }
        .admit-card img {
            max-width: 100px;
            height: auto;
        }
        .signature {
            text-align: right;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container admit-card" id="admitCard">
        <div class="text-center mb-4">
            <img src="path_to_logo/logo.png" alt="Board Logo" class="mb-2">
            <h3>BOARD OF SECONDARY EDUCATION, MADHYA PRADESH, BHOPAL</h3>
            <p>Higher Secondary School Certificate Examination (10+2)</p>
            <p><strong>May-June 2024 Examinations</strong></p>
        </div>
        <div class="row mb-3">
            <div class="col-md-8">
                <p><strong>Roll Number:</strong> <?php echo $student['full_name']; ?></p>
                <p><strong>Candidate's Name:</strong> <?php echo $student['gender']; ?></p>
                <p><strong>D.O.B:</strong> <?php echo $student['dob']; ?></p>
                <p><strong>Father's Name:</strong> <?php echo $student['father_name']; ?></p>
                <p><strong>Mother's Name:</strong> <?php echo $student['mother_name']; ?></p>
                <p><strong>Address:</strong> <?php echo $student['address']; ?></p>
                <p><strong>E Mail:</strong> <?php echo $student['email']; ?></p>
                <p><strong>Exam Center:</strong> <?php echo $student['center1']; ?></p>
            </div>
            <div class="col-md-4 text-center">
                <img src="<?php echo $student['photo_path']; ?>" alt="Student Photo" class="img-thumbnail">
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Theory Exam Date & Time</th>
                    <th>Paper Code</th>
                    <th>Subject</th>
                    <th>Opted by Student</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($exams as $exam): ?>
                    <tr>
                        <td><?php echo $exam['subject']; ?></td>
                        <td><?php echo $exam['id']; ?></td>
                        <td><?php echo $exam['syllabus']; ?></td>
                        <td><?php echo $exam['student_code']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="signature">
            <p>Signature</p>
        </div>
    </div>
    <div class="text-center">
        <button class="btn btn-primary" onclick="downloadPDF()">Download as PDF</button>
    </div>

    <script>
        async function downloadPDF() {
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF();
            const element = document.getElementById('admitCard');
            
            // Convert the content to PDF
            pdf.html(element, {
                callback: function (doc) {
                    doc.save('Admit_Card.pdf');
                },
                x: 10,
                y: 10
            });
        }
    </script>
</body>
</html>
