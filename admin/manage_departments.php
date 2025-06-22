<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// ADD Department
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    if (!empty($name)) {
        mysqli_query($conn, "INSERT INTO departments (name) VALUES ('$name')");
    }
}

// UPDATE Department
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    mysqli_query($conn, "UPDATE departments SET name='$name' WHERE id=$id");
}

// DELETE Department
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM departments WHERE id=$id");
}

// Fetch all departments
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Departments</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        table { width: 50%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; padding: 10px; }
        h2 { color: teal; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 5px; width: 200px; }
        input[type="submit"] { padding: 5px 10px; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>

<h2>Manage Departments</h2>

<!-- Add Department -->
<form method="POST">
    <input type="text" name="name" placeholder="New Department Name" required />
    <input type="submit" name="add" value="Add" />
</form>

<!-- Display Departments -->
<table>
    <tr>
        <th>ID</th>
        <th>Department Name</th>
        <th>Action</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($departments)): ?>
    <tr>
        <form method="POST">
            <td><?php echo $row['id']; ?></td>
            <td>
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                <input type="text" name="name" value="<?php echo $row['name']; ?>" />
            </td>
            <td>
                <input type="submit" name="update" value="Update" />
                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this department?')">Delete</a>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
