<?php 
include "connection.php";

if (isset($_POST['btnsubmit'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Prepare the main `exams` insert query
        $stmt = $conn->prepare("INSERT INTO exams (admin_id,
            subject, num_questions, marks_correct, marks_incorrect, marks_unattempted, 
            duration, reg_start, reg_end, admit_card_issue, admit_card_expire, 
            exam_start, exam_end, teacher_username, teacher_password, venue, 
            student_code, max_students, syllabus, result_issue, result_expire
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }

        $pass = password_hash($_POST['teacher_password'], PASSWORD_DEFAULT); 

        // Bind parameters for `exams`
        $stmt->bind_param(
            'ssidddissssssssssisss',
            $_SESSION['username'],
            $_POST['subject'],                
            $_POST['num_questions'],          
            $_POST['marks_correct'],          
            $_POST['marks_wrong'],        
            $_POST['marks_unattempted'],      
            $_POST['duration'],               
            $_POST['reg_start'],              
            $_POST['reg_end'],                
            $_POST['admit_card_issue'],       
            $_POST['admit_card_expire'],      
            $_POST['exam_start'],             
            $_POST['exam_end'],               
            $_POST['teacher_username'],       
            $pass,
            $_POST['venue'],                  
            $_POST['student_code'],           
            $_POST['max_students'],           
            $_POST['syllabus'],               
            $_POST['result_issue'],           
            $_POST['result_expire']           
        );

        // Check if the username already exists in `users` table
        $name = $_POST['teacher_username'];
        $check_query = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $check_query->bind_param('s', $name);
        $check_query->execute();
        $check_query->store_result();

        if ($check_query->num_rows > 0) {
            // Username already exists, skip user insertion
            echo "<script>alert('Teacher username already exists. Skipping user creation.');</script>";
        } else {
            // Prepare the `users` insert query
            $stmt2 = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
            if (!$stmt2) {
                die("Error preparing statement: " . $conn->error);
            }

            // Bind parameters for `users`
            $type = 'Teacher';
            $stmt2->bind_param('sss', $name, $pass, $type);

            // Execute `users` insertion
            if (!$stmt2->execute()) {
                echo "Error inserting into users: " . $stmt2->error;
            }

            $stmt2->close();
        }

        // Execute the main `exams` insertion
        if ($stmt->execute()) {
            header("Location: admin_dashboard.php?message=success");
            exit();
        } else {
            echo "Error inserting into exams: " . $stmt->error;
        }

        // Close the statements
        $check_query->close();
        $stmt->close();
        $conn->close();
    }
}
?>
