<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Add Leave Type
if (isset($_POST['add'])) {
    $type_name = $_POST['type_name'];
    mysqli_query($conn, "INSERT INTO leave_types (type_name) VALUES ('$type_name')");
}

// Delete Leave Type
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM leave_types WHERE id=$id");
}

// Update Leave Type
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $type_name = $_POST['type_name'];
    mysqli_query($conn, "UPDATE leave_types SET type_name='$type_name' WHERE id=$id");
}

// Get all leave types
$leave_types = mysqli_query($conn, "SELECT * FROM leave_types ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Leave Types</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        form, table { background: #fff; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        input { padding: 8px; width: 100%; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        .btn-delete { background: red; color: white; padding: 5px 8px; text-decoration: none; }
        .btn-edit { background: green; color: white; padding: 5px 8px; text-decoration: none; }
    </style>
</head>
<body>

<h2>Add Leave Type</h2>
<form method="POST">
    <input type="text" name="type_name" placeholder="e.g. Sick Leave" required>
    <input type="submit" name="add" value="Add Leave Type">
</form>

<h2>All Leave Types</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Leave Type</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($leave_types)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td>
            <form method="POST" style="display: flex; gap: 5px;">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <input type="text" name="type_name" value="<?= $row['type_name'] ?>" required>
                <input type="submit" name="update" value="Update">
            </form>
        </td>
        <td>
            <a class="btn-delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this leave type?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
