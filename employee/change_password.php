<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$id = $_SESSION['user_id'];
$msg = "";
$alertClass = "";

if (isset($_POST['change'])) {
    $current = $_POST['current'];
    $new = $_POST['new'];

    $res = mysqli_query($conn, "SELECT password FROM employees WHERE id=$id");
    $row = mysqli_fetch_assoc($res);

    // Verify hashed password
    if (password_verify($current, $row['password'])) {
        $hashedNew = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE employees SET password='$hashedNew' WHERE id=$id");
        $msg = "Password changed successfully!";
        $alertClass = "success";
    } else {
        $msg = "Current password is incorrect.";
        $alertClass = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
        }
        .password-container {
            max-width: 500px;
            margin: 70px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="password-container">
    <h2 class="text-center text-primary mb-4">Change Password</h2>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $alertClass ?> text-center"><?= $msg ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="new" class="form-control" required>
        </div>

        <button type="submit" name="change" class="btn btn-success w-100">Change Password</button>
    </form>

    <div class="text-center mt-4">
        <a href="dashboard.php" class="btn btn-outline-secondary">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
