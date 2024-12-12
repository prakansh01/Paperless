<?php include "connection.php";

$admin_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_center'])) {

        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $total_systems = intval($_POST['total_systems']);
        $available_systems = $_POST['total_systems'];
        $busy_systems = $total_systems - $available_systems;

        $conn->query("INSERT INTO centers (admin_id, name, address, total_systems, available_systems, busy_systems) VALUES ($admin_id, '$name', '$address', $total_systems, $available_systems, $busy_systems)");

        header("Location: " . $_SERVER['PHP_SELF']);
    } elseif (isset($_POST['edit_center'])) {

        $id = intval($_POST['id']);
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $total_systems = intval($_POST['total_systems']);
        $available_systems = intval($_POST['available_systems']);
        $busy_systems = $total_systems - $available_systems;

        $conn->query("UPDATE centers SET name = '$name', address = '$address', total_systems = $total_systems, available_systems = $available_systems, busy_systems = $busy_systems WHERE id = $id AND admin_id = $admin_id");

        header("Location: " . $_SERVER['PHP_SELF']);
    } elseif (isset($_POST['delete_center'])) {

        $id = intval($_POST['id']);
        $conn->query("DELETE FROM centers WHERE id = $id AND admin_id = $admin_id");

        header("Location: " . $_SERVER['PHP_SELF']);
    }
}

$centers = $conn->query("SELECT * FROM centers WHERE admin_id = $admin_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Centers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Manage Centers</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Total Systems</th>
                        <th>Available Systems</th>
                        <th>Busy Systems</th>
                        <th>Allocated To Exam_id</th>
                        <th colspan="2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($centers->num_rows > 0): ?>
                        <?php while ($center = $centers->fetch_assoc()): ?>
                            <tr>
                                <td><?= $center['id'] ?></td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="id" value="<?= $center['id'] ?>">
                                    <td><input type="text" class="form-control" name="name"
                                            value="<?= htmlspecialchars($center['name']) ?>" required></td>
                                    <td><input type="text" class="form-control" name="address"
                                            value="<?= htmlspecialchars($center['address']) ?>" required></td>
                                    <td style="width:110px;"><input type="number" class="form-control" name="total_systems"
                                            value="<?= $center['total_systems'] ?>" required></td>
                                    <td style="width:110px;"><?= $center['available_systems'] ?></td>        
                                    <td style="width:110px;"><?= $center['busy_systems'] ?></td>
                                    <td style="width:110px;"><?= $center['allocated_to'] ?></td>
                                    <td><button type="submit" name="edit_center" class="btn btn-primary btn-sm">Edit</button>
                                    </td>
                                </form>
                                <form method="POST">
                                    <input type="hidden" name="id" value="<?= $center['id'] ?>">
                                    <td><button type="submit" name="delete_center" class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </form>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- No Centers Found -->
                        <p class="text-center text-muted">No centers found. Add a new center below.</p>
                    <?php endif; ?>
                    <tr>
                        <form method="POST" class="row g-3 justify-content-center">
                            <td>ID</td>
                            <td><input type="text" id="name" name="name" class="form-control" required></td>
                            <td><textarea style="height:35px;" id="address" name="address" class="form-control" required></textarea></td>
                            <td style="width:110px;"><input type="number" id="total_systems" name="total_systems" class="form-control"
                                    required></td>
                            <td colspan="3"></td>
                            <td colspan="2"><button type="submit" name="add_center" class="btn btn-success">Add Center</button>
                            </td>
                        </form>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>