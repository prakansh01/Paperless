<?php
include "connection.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // 'login' or 'register'
    $username = $_POST['username']; // No need to escape, prepared statements handle it
    $password = $_POST['password'];

    if ($action === 'login') {
        // Login process using prepared statements
        $stmt = $conn->prepare("SELECT id, password, user_type FROM users WHERE username = ?");
        $stmt->bind_param("s", $username); // Bind the username as a string
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            $user_type = $row['user_type'];

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $username;
                $_SESSION['user_type'] = $user_type;
                // Redirect based on user type
                switch ($user_type) {
                    case 'teacher':
                        header("Location: teacher_dashboard.php");
                        break;
                    case 'student':
                        header("Location: student_dashboard.php");
                        break;
                    case 'admin':
                        header("Location: admin_dashboard.php");
                        break;
                    default:
                        echo "Invalid user type.";
                        exit();
                }
                exit();
            } else {
                echo "Invalid username or password.";
            }
        } else {
            echo "Invalid username or password.";
        }
        $stmt->close(); // Close the statement
    } elseif ($action === 'register') {
        // Registration process using prepared statements
        $user_type = $_POST['user_type']; // No need to escape, prepared statements handle it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $user_type); // Bind the parameters as strings



        if ($stmt->execute()) {
            $stmt2 = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt2->bind_param("s", $username);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = $result2->fetch_assoc();
            $_SESSION["user_id"] = $row2['id'];
            $_SESSION['username'] = $username;
            $_SESSION['user_type'] = $user_type;
            // Redirect to appropriate dashboard after successful registration
            switch ($user_type) {
                case 'teacher':
                    header("Location: teacher_dashboard.php");
                    break;
                case 'student':
                    header("Location: student_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                    echo "Invalid user type during registration.";
                    exit();
            }
            exit();
        } else {
            if ($stmt->errno === 1062) { // Duplicate entry error code
                echo "Error: Username already exists.";
            } else {
                echo "Error: " . $stmt->error;
            }
        }
        $stmt->close(); // Close the statement
    }
}

$conn->close(); // Close the database connection
?>