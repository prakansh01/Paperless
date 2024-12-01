<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "paperless_db"; // Replace with your actual database name

// Establish connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST data from the form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
    $center_name = $conn->real_escape_string($_POST['centerName']);
    $address = $conn->real_escape_string($_POST['address']);
    $available_computers = intval($_POST['computers']);

    // Ensure required fields are provided
    if ($admin_id > 0 && !empty($center_name) && !empty($address) && $available_computers > 0) {
        // Insert data into the centers table
        $sql = "INSERT INTO centers (admin_id, center_name, address, available_computers)
                VALUES ($admin_id, '$center_name', '$address', $available_computers)";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Center added successfully!'); window.location.href = 'admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href = 'admin_dashboard.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill out all fields correctly!'); window.location.href = 'admin_dashbard.php';</script>";
    }
}

// Close connection
$conn->close();
?>
