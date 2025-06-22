<?php
include('includes/db.php');

$message = "";

if (isset($_POST['submit'])) {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $dob      = $_POST['dob'];
    $department_id = $_POST['department_id'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = " This email is already registered.";
    } else {
        $sql = "INSERT INTO employees (name, email, phone, address, dob, department_id, password, role) 
                VALUES ('$name', '$email', '$phone', '$address', '$dob', '$department_id', '$password', 'employee')";
        if (mysqli_query($conn, $sql)) {
            $message = "Registered successfully. You can now login.";
        } else {
            $message = "Registration failed: " . mysqli_error($conn);
        }
    }
}

// Get department list
$departments = mysqli_query($conn, "SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Registration</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; }
        form { background: #fff; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; }
        input, select { width: 100%; padding: 8px; margin-bottom: 12px; }
        button { background: #007bff; color: white; padding: 10px; border: none; border-radius: 4px; cursor: pointer; }
        .msg { text-align: center; color: red; font-weight: bold; margin-bottom: 15px; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Employee Registration</h2>
<?php if($message): ?>
    <p class="msg"><?= $message ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="text" name="phone" placeholder="Phone Number" required>
    <textarea name="address" placeholder="Mailing Address" required></textarea>
    <input type="date" name="dob" required>
    
    <select name="department_id" required>
        <option value="">Select Department</option>
        <?php while($dept = mysqli_fetch_assoc($departments)): ?>
            <option value="<?= $dept['id'] ?>"><?= $dept['name'] ?></option>
        <?php endwhile; ?>
    </select>
    
    <input type="password" name="password" placeholder="Set a Password" required>
    <button type="submit" name="submit">Register</button>
</form>

<p style="text-align:center; margin-top: 20px;">Already have an account? <a href="login.php">Login here</a></p>

</body>
</html>
