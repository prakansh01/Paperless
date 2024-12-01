
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

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam_id = $_POST['exam_id'] ?? null; // Ensure exam_id is passed
    $responses = $_POST['responses'] ?? null; // Ensure responses are passed

    if ($exam_id && $responses) {
        $responses = json_decode($responses, true); // Decode the JSON responses

        if (is_array($responses)) {
            // Insert responses into the database
            $insertQuery = "INSERT INTO student_responses (exam_id, question_id, selected_option, student_id, status) 
                VALUES (:exam_id, :question_id, :selected_option, :student_id, :status)";

            $stmt = $pdo->prepare($insertQuery);

            // Simulate student ID (replace with real logic to get student ID)
            $student_id = 1;

            foreach ($responses as $response) {
                $stmt->execute([
                    ':exam_id' => $exam_id,
                    ':question_id' => $response['question_id'],
                    ':selected_option' => $response['status'],
                    ':student_id' => $student_id,
                    ':status' => $response['status'],
                ]);
            }

            echo "Responses submitted successfully!";
        } else {
            echo "Invalid response format.";
        }
    } else {
        echo "Missing exam ID or responses.";
    }
} else {
    echo "Invalid request method.";
}
