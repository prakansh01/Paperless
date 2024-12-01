<?php include "connection.php";

// Check if student 'id' is provided via GET
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $form_id = intval($_GET['id']); // Sanitize the input
    $sql = "DELETE FROM exam_application WHERE id = ?";
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

// Close the connection
$conn->close();
?>