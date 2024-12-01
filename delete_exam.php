<?php include "connection.php";
// Check if 'id' is provided via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $exam_id = intval($_GET['id']); // Sanitize the input

    // Prepare the DELETE query
    $sql = "DELETE FROM exams WHERE id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $exam_id);

        // Execute the statement
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=exam_deleted");
            exit();
        } else {
            echo "Error deleting exam: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error preparing the statement: " . $conn->error;
    }
} else {
    echo "No exam ID provided or invalid request.";
}

// Close the connection
$conn->close();
?>
