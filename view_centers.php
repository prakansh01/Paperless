<?php 
include "connection.php";

// Handle Delete Action
if (isset($_POST['delete'])) {
    $center_id = intval($_POST['center_id']);
    $delete_query = "DELETE FROM centers WHERE id = $center_id";
    mysqli_query($conn, $delete_query);
}

// Handle Edit Action
if (isset($_POST['edit'])) {
    $center_id = intval($_POST['center_id']);
    $center_name = mysqli_real_escape_string($conn, $_POST['center_name']);
    $center_address = mysqli_real_escape_string($conn, $_POST['center_address']);
    $available_systems = intval($_POST['available_systems']);

    $edit_query = "UPDATE centers 
                   SET center_name = '$center_name', address = '$center_address', available_computers = $available_systems 
                   WHERE id = $center_id";
    mysqli_query($conn, $edit_query);
}

// Get Admin ID from URL
$id = intval($_GET['id']);

// Fetch Centers
$query = "SELECT * FROM centers WHERE admin_id = $id";
$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Centers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center mb-4">All Centers</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Center Name</th>
                        <th>Center Address</th>
                        <th>Available Systems</th>
                        <th colspan="2" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_assoc($data)) {
                            ?>
                            <tr>
                                <form method="POST" action="">
                                    <input type="hidden" name="center_id" value="<?php echo $row['id']; ?>">
                                    <td>
                                        <input type="text" name="center_name" class="form-control" 
                                               value="<?php echo $row['center_name']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" name="center_address" class="form-control" 
                                               value="<?php echo $row['address']; ?>">
                                    </td>
                                    <td>
                                        <input type="number" name="available_systems" class="form-control" 
                                               value="<?php echo $row['available_computers']; ?>">
                                    </td>
                                    <td>
                                        <button type="submit" name="edit" class="btn btn-primary">Save</button>
                                    </td>
                                    <td>
                                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                                    </td>
                                </form>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="5" class="text-center">No Records Found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- Back to Dashboard Button -->
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-6NTB85kQ4JvnLq8SEuVv9pgN4/7SFlMQgOVHIzUZqhfvIjJCPBXsQpTTi26x9ORx"
            crossorigin="anonymous"></script>
</body>
</html>
