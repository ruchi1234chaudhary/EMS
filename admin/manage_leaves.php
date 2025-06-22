<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Approve Leave
if (isset($_GET['approve'])) {
    $id = intval($_GET['approve']);
    mysqli_query($conn, "UPDATE leaves SET status='Approved' WHERE id=$id");
}

// Reject Leave
if (isset($_GET['reject'])) {
    $id = intval($_GET['reject']);
    mysqli_query($conn, "UPDATE leaves SET status='Rejected' WHERE id=$id");
}

// Fetch all leave requests with employee name
$leaves = mysqli_query($conn, "
    SELECT l.*, e.name AS employee_name 
    FROM leaves l 
    LEFT JOIN employees e ON l.employee_id = e.id 
    ORDER BY l.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Leave Requests</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        a { padding: 5px 10px; text-decoration: none; margin-right: 5px; }
        .approve { background: green; color: white; }
        .reject { background: red; color: white; }
    </style>
</head>
<body>

<h2>Manage Leave Requests</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Employee</th>
        <th>Type</th>
        <th>From</th>
        <th>To</th>
        <th>Reason</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
    <?php while($row = mysqli_fetch_assoc($leaves)): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['employee_name']; ?></td>
        <td><?php echo $row['leave_type']; ?></td>
        <td><?php echo $row['from_date']; ?></td>
        <td><?php echo $row['to_date']; ?></td>
        <td><?php echo $row['reason']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
            <?php if ($row['status'] == 'Pending'): ?>
                <a class="approve" href="?approve=<?php echo $row['id']; ?>">Approve</a>
                <a class="reject" href="?reject=<?php echo $row['id']; ?>">Reject</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<p><a href="dashboard.php">← Back to Dashboard</a></p>

</body>
</html>
