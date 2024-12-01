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

// Replace with the desired exam ID
$exam_id = 4;

// Fetch exam name
$query = "SELECT subject FROM exams WHERE id = :exam_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$stmt->execute();
$exam = $stmt->fetch(PDO::FETCH_ASSOC);

// If no record is found, handle it
if (!$exam) {
    $exam_name = "Exam not found";
} else {
    $exam_name = htmlspecialchars($exam['subject']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Name Display</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-center align-items-center bg-light border border-secondary rounded p-3" style="height: 100px;">
            <h1 class="mb-0 text-center text-dark font-weight-bold">
                <?php echo $exam_name; ?>
            </h1>
        </div>
    </div>
</body>

</html>
