<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Add salary
if (isset($_POST['add'])) {
    $emp_id = $_POST['employee_id'];
    $month = $_POST['month'];
    $amount = $_POST['amount'];
    $paid_on = $_POST['paid_on'];

    mysqli_query($conn, "INSERT INTO salaries (employee_id, month, amount, paid_on)
                         VALUES ('$emp_id', '$month', '$amount', '$paid_on')");
}

// Delete salary
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM salaries WHERE id = $id");
}

// Fetch employees for dropdown
$employees = mysqli_query($conn, "SELECT id, name FROM employees WHERE role='employee'");

// Fetch all salaries
$salaries = mysqli_query($conn, "
    SELECT s.*, e.name AS employee_name 
    FROM salaries s 
    JOIN employees e ON s.employee_id = e.id 
    ORDER BY s.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Salaries</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        form { background: #fff; padding: 20px; margin-bottom: 20px; border-radius: 8px; }
        input, select { padding: 8px; margin: 8px 0; width: 100%; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; }
        .delete { background: red; color: white; padding: 4px 8px; text-decoration: none; }
    </style>
</head>
<body>

<h2>Add Salary</h2>

<form method="POST">
    <label>Employee</label>
    <select name="employee_id" required>
        <option value="">-- Select Employee --</option>
        <?php while($emp = mysqli_fetch_assoc($employees)): ?>
            <option value="<?php echo $emp['id']; ?>"><?php echo $emp['name']; ?></option>
        <?php endwhile; ?>
    </select>

    <label>Month</label>
    <input type="text" name="month" placeholder="e.g. June 2025" required>

    <label>Amount</label>
    <input type="number" name="amount" step="0.01" required>

    <label>Paid On</label>
    <input type="date" name="paid_on" required>

    <input type="submit" name="add" value="Add Salary">
</form>

<h2>Salary Records</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Employee</th>
        <th>Month</th>
        <th>Amount (₹)</th>
        <th>Paid On</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($salaries)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['employee_name']; ?></td>
        <td><?php echo $row['month']; ?></td>
        <td><?php echo number_format($row['amount'], 2); ?></td>
        <td><?php echo $row['paid_on']; ?></td>
        <td><a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this salary record?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
