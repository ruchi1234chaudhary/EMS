<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$employee_id = $_SESSION['user_id'];

// Fetch salary history
$salaries = mysqli_query($conn, "
    SELECT * FROM salaries 
    WHERE employee_id = $employee_id 
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Salary History</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        h2 { margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>My Salary History</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Month</th>
        <th>Amount (₹)</th>
        <th>Paid On</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($salaries)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['month']; ?></td>
        <td><?php echo number_format($row['amount'], 2); ?></td>
        <td><?php echo $row['paid_on']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
