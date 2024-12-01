<?php include "connection.php";
$id = $_GET['id'];
$query = "SELECT * FROM questions WHERE question_id='$id' ";
$data = mysqli_query($conn, $query);
$row = mysqli_fetch_row($data);
?>










<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Modal-like form */
        .form-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: flex; /* Hidden by default */
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







        <!-- Form Overlay -->
        <div id="formOverlay" class="form-overlay">
        <div class="form-box">
            <h4 class="text-center mb-3">Edit Question</h4>
            <form action="editquestion.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                                <input value="<?php echo ($row[2]);?>" type="text" class="form-control question-input" name="question" style="width:500px;" required>
                            </td>
                            <td>
                                <input value="<?php echo ($row[3]);?>"  type="text" class="form-control answer-input" name="correct_answer" required>
                            </td>
                            <td>
                                <input value="<?php echo ($row[4]);?>"  type="text" class="form-control answer-input" name="option1" required>
                            </td>
                            <td>
                                <input value="<?php echo ($row[5]);?>"  type="text" class="form-control answer-input" name="option2" required>
                            </td>
                            <td>
                                <input value="<?php echo ($row[6]);?>"  type="text" class="form-control answer-input" name="option3" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="d-flex justify-content-around align-items-center mt-3">
                                    <input type="submit" name="addquestionbtn" class="btn btn-success"></input>
                                    <input type="reset" class="btn btn-danger"></input>
                                    <a class="btn btn-secondary" href="teacher_dashboard.php" role="button">Cancel</a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>








    <?php
if (isset($_POST['addquestionbtn'])) {
    $id = $_POST['id'];
    $question = $_POST['question'];
    $correct_answer = $_POST['correct_answer'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];

    $update = "UPDATE `questions` SET `question` = '$question', `correct_answer` = '$correct_answer', `option1` = '$option1' , `option2` = '$option2' , `option3` = '$option3' WHERE `question_id` = '$id'";
    $data = mysqli_query($conn, $update);
    if ($data) {
        ?>
        <script type="text/javascript">
            alert("Question Updated Successfully");
            window.open("http://localhost/PHP%20codes/PaperLess/teacher_dashboard.php","_self");
        </script>
    <?php
    }else {
        ?>
        <script type="text/javascript">
            alert("Please try Again");
        </script>
        <?php 
    }
}

?>


























    
</body>
</html>