<?php
include "connection.php"; // Include your database connection file

// Handle adding question 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $question = $_POST['question'];
    $correct_answer = $_POST['correct_answer'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];

    // Save data to the database
    $stmt = $conn->prepare("INSERT INTO questions (question, correct_answer, option1, option2, option3) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $question, $correct_answer, $option1, $option2, $option3);

    if ($stmt->execute()) {
        echo "<script>alert('Question added successfully!');</script>";
    } else {
        echo "<script>alert('Failed to add question: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modal-like form */
        .form-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1050; /* Higher than most Bootstrap components */
        }

        .form-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 70%; /* Wider form for horizontal layout */
        }

        .form-table {
            width: 100%;
            border-collapse: collapse;
        }

        .form-table th,
        .form-table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .form-table th {
            background-color: #f8f9fa;
        }

        .question-input {
            width: 100%;
            height: 50px; /* Make question input box larger */
        }

        .answer-input {
            width: 100%;
        }
    </style>
</head>
<body>
    <!-- Add Button -->
    <div class="add-btn">
        <button id="addButton" class="btn btn-primary" onclick="showForm()">Add Question</button>
    </div>

    <!-- Form Overlay -->
    <div id="formOverlay" class="form-overlay">
        <div class="form-box">
            <h4 class="text-center mb-3">Add Question</h4>
            <form method="POST">
                <table class="form-table">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Correct Answer</th>
                            <th>Option 1</th>
                            <th>Option 2</th>
                            <th>Option 3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control question-input" name="question" style="width:500px;" required>
                            </td>
                            <td>
                                <input type="text" class="form-control answer-input" name="correct_answer" required>
                            </td>
                            <td>
                                <input type="text" class="form-control answer-input" name="option1" required>
                            </td>
                            <td>
                                <input type="text" class="form-control answer-input" name="option2" required>
                            </td>
                            <td>
                                <input type="text" class="form-control answer-input" name="option3" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success me-3">Submit</button>
                                    <button type="button" class="btn btn-secondary" onclick="hideForm()">Cancel</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show the form overlay
        function showForm() {
            document.getElementById('formOverlay').style.display = 'flex';
        }

        // Function to hide the form overlay
        function hideForm() {
            document.getElementById('formOverlay').style.display = 'none';
        }
    </script>
</body>
</html>
