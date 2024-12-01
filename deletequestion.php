<?php include "connection.php"; 
$id = $_GET['id'];
$query = "DELETE FROM questions WHERE question_id='$id' ";
$data = mysqli_query($conn, $query);
if($data)
{
    ?>
    <script type="text/javascript">
        alert("Question Deleted Successfully");
        window.open("http://localhost/PHP%20codes/PaperLess/teacher_dashboard.php","_self");
    </script>
        <?php
}
else{
    ?>
    <script type="text/javascript">
        alert("Some error occured ");
    </script>
        <?php
}

?>