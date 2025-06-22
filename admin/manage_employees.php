<?php
session_start();
include('../includes/db.php');

// Check admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Fetch departments for dropdown
$departments = mysqli_query($conn, "SELECT * FROM departments");

// Add Employee
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $dob = $_POST['dob'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO employees (name, email, phone, address, dob, department_id, password, role)
                         VALUES ('$name', '$email', '$phone', '$address', '$dob', $department, '$password', 'employee')");
}

// Delete Employee
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM employees WHERE id=$id");
}

// Fetch all employees (only role = employee)
$employees = mysqli_query($conn, "
    SELECT e.*, d.name as department 
    FROM employees e 
    LEFT JOIN departments d ON e.department_id = d.id 
    WHERE e.role='employee'
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Employees</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        form { margin-bottom: 20px; }
        input, select { padding: 5px; margin: 5px; }
        a { color: red; text-decoration: none; }
    </style>
</head>
<body>

<h2>Manage Employees</h2>

<!-- Add New Employee -->
<form method="POST">
    <input type="text" name="name" placeholder="Name" required />
    <input type="email" name="email" placeholder="Email" required />
    <input type="text" name="phone" placeholder="Phone" required />
    <input type="text" name="address" placeholder="Address" required />
    <input type="date" name="dob" required />
    <select name="department" required>
        <option value="">Select Department</option>
        <?php while($dept = mysqli_fetch_assoc($departments)): ?>
            <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <input type="password" name="password" placeholder="Password" required />
    <input type="submit" name="add" value="Add Employee" />
</form>

<!-- Show Employees -->
<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Department</th>
        <th>DOB</th>
        <th>Action</th>
    </tr>
    <?php while($emp = mysqli_fetch_assoc($employees)): ?>
    <tr>
        <td><?php echo $emp['id']; ?></td>
        <td><?php echo $emp['name']; ?></td>
        <td><?php echo $emp['email']; ?></td>
        <td><?php echo $emp['phone']; ?></td>
        <td><?php echo $emp['department']; ?></td>
        <td><?php echo $emp['dob']; ?></td>
        <td>
            <a href="?delete=<?php echo $emp['id']; ?>" onclick="return confirm('Delete this employee?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
