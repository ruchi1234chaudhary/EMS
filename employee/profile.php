<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['user_id'];

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    mysqli_query($conn, "UPDATE employees SET name='$name', phone='$phone', address='$address' WHERE id=$id");
    $msg = "Profile Updated!";
}

$result = mysqli_query($conn, "SELECT * FROM employees WHERE id=$id");
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .profile-container {
            max-width: 600px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2 class="text-center text-primary mb-4">My Profile</h2>

    <?php if (isset($msg)): ?>
        <div class="alert alert-success text-center"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Full Name:</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($row['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Phone:</label>
            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($row['phone']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Address:</label>
            <textarea name="address" class="form-control" rows="3"><?= htmlspecialchars($row['address']) ?></textarea>
        </div>

        <button type="submit" name="update" class="btn btn-success w-100">Update Profile</button>
    </form>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-primary">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>

