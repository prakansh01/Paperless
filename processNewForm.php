<?php include "connection.php";

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fullName = $_POST["fullName"];
    $gender = $_POST["gender"];
    $fatherName = $_POST["fatherName"];
    $motherName = $_POST["motherName"];
    $dob = $_POST["dob"];
    $category = $_POST["category"];
    $email = $_POST["email"];
    $address = $_POST["address"];
    $phone = $_POST["phone"];
    $state = $_POST["state"];
    $whatsapp = $_POST["whatsapp"];
    $city = $_POST["city"];
    $tenthPercentage = $_POST["10thPercentage"];
    $twelfthPercentage = $_POST["12thPercentage"];
    $stream = $_POST["subject"];
    $hobby = $_POST["hobby"];
    $competition = $_POST["competition"];
    $rank = $_POST["rank"];
    $examId = $_POST["examId"];
    $studentCode = $_POST["studentCode"];
    $center1 = $_POST["center1"];
    $center2 = $_POST["center2"];
    $center3 = $_POST["center3"];

    // Handle file uploads for photo and signature
    $photoPath = null;
    $signaturePath = null;


    if (!file_exists('uploads/photos')) {
        mkdir('uploads/photos', 0777, true); // Create directory with permissions
    }
    if (!file_exists('uploads/signatures')) {
        mkdir('uploads/signatures', 0777, true);
    }
    


    if (isset($_FILES["photo"])) {
        $photoPath = "uploads/photos/" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);
    }

    if (isset($_FILES["signature"])) {
        $signaturePath = "uploads/signatures/" . basename($_FILES["signature"]["name"]);
        move_uploaded_file($_FILES["signature"]["tmp_name"], $signaturePath);
    }

    // Prepare SQL query
    $stmt = $conn->prepare(
        "INSERT INTO exam_application (
            full_name, gender, father_name, mother_name, dob, category, 
            email, address, phone, state, whatsapp, city, tenth_percentage, 
            twelfth_percentage, stream, hobby, competition, rank, exam_id, 
            student_code, center1, center2, center3, photo_path, signature_path
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssssssssssddsssisssssss",
        $fullName, $gender, $fatherName, $motherName, $dob, $category,
        $email, $address, $phone, $state, $whatsapp, $city, $tenthPercentage,
        $twelfthPercentage, $stream, $hobby, $competition, $rank, $examId,
        $studentCode, $center1, $center2, $center3, $photoPath, $signaturePath
    );

    // Execute and check for errors
    if ($stmt->execute()) {
        header("Location: success.html");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
