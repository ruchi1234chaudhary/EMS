<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$employee_id = $_SESSION['user_id'];

// Apply for leave
if (isset($_POST['apply'])) {
    $leave_type = $_POST['leave_type'];
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);

    mysqli_query($conn, "INSERT INTO leaves (employee_id, leave_type, from_date, to_date, reason)
                         VALUES ($employee_id, '$leave_type', '$from_date', '$to_date', '$reason')");
}

// Get employee's leave history
$leave_history = mysqli_query($conn, "
    SELECT * FROM leaves 
    WHERE employee_id = $employee_id 
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Leaves</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f8f8f8; }
        form { margin-bottom: 30px; background: #fff; padding: 15px; border-radius: 5px; }
        input, select, textarea { margin: 5px 0; padding: 7px; width: 100%; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        h2 { margin-bottom: 10px; }
    </style>
</head>
<body>

<h2>Apply for Leave</h2>

<form method="POST">
    <label>Leave Type</label>
    <select name="leave_type" required>
        <option value="">Select Type</option>
        <option value="Casual Leave">Casual Leave</option>
        <option value="Medical Leave">Medical Leave</option>
        <option value="Paid Leave">Paid Leave</option>
    </select>

    <label>From Date</label>
    <input type="date" name="from_date" required>

    <label>To Date</label>
    <input type="date" name="to_date" required>

    <label>Reason</label>
    <textarea name="reason" required></textarea>

    <input type="submit" name="apply" value="Apply Leave">
</form>

<h2>Leave History</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Type</th>
        <th>From</th>
        <th>To</th>
        <th>Reason</th>
        <th>Status</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($leave_history)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['leave_type']; ?></td>
        <td><?php echo $row['from_date']; ?></td>
        <td><?php echo $row['to_date']; ?></td>
        <td><?php echo $row['reason']; ?></td>
        <td><?php echo $row['status']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
