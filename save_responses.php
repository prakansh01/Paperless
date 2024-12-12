<?php
include "connection.php";


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "error" => "Invalid JSON data"]);
    exit;
}

$exam_id = $data['exam_id'];
$student_id = $data['student_id'];
$responses = $data['responses'];

$query = "INSERT INTO student_responses (student_id, exam_id, question_id, selected_option, response_status) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);


if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Failed to prepare statement"]);
    exit;
}

foreach ($responses as $response) {
    $stmt->bind_param("iiiss", $student_id, $exam_id, $response['question_id'], $response['selected_option'], $response['response_status']);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "error" => $stmt->error]);
        exit;
    }
}

echo json_encode(["success" => true]);

?>
