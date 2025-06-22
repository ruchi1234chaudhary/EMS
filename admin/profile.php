<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$admin_id = $_SESSION['user_id'];

// Update Profile
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    mysqli_query($conn, "UPDATE employees SET name='$name', email='$email', phone='$phone' WHERE id=$admin_id");
    $msg = "Profile Updated!";
}

// Change Password
if (isset($_POST['change_pass'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $result = mysqli_query($conn, "SELECT password FROM employees WHERE id=$admin_id");
    $row = mysqli_fetch_assoc($result);

    if ($old == $row['password']) {
        if ($new == $confirm) {
            mysqli_query($conn, "UPDATE employees SET password='$new' WHERE id=$admin_id");
            $msg = "Password Changed!";
        } else {
            $msg = "New passwords do not match!";
        }
    } else {
        $msg = "Old password is incorrect!";
    }
}

// Fetch Admin Info
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM employees WHERE id=$admin_id"));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f2f2f2; }
        form { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 6px; }
        input { width: 100%; padding: 8px; margin: 8px 0; }
        h2 { margin-bottom: 10px; }
        .msg { padding: 10px; background: #d4edda; color: #155724; border-radius: 5px; }
    </style>
</head>
<body>

<h2>Admin Profile</h2>

<?php if (isset($msg)): ?>
    <div class="msg"><?php echo $msg; ?></div>
<?php endif; ?>

<form method="POST">
    <label>Name</label>
    <input type="text" name="name" value="<?php echo $admin['name']; ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?php echo $admin['email']; ?>" required>

    <label>Phone</label>
    <input type="text" name="phone" value="<?php echo $admin['phone']; ?>" required>

    <input type="submit" name="update_profile" value="Update Profile">
</form>

<h2>Change Password</h2>

<form method="POST">
    <label>Old Password</label>
    <input type="password" name="old_password" required>

    <label>New Password</label>
    <input type="password" name="new_password" required>

    <label>Confirm New Password</label>
    <input type="password" name="confirm_password" required>

    <input type="submit" name="change_pass" value="Change Password">
</form>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
