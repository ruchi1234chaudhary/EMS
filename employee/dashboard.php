<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: ../login.php");
    exit;
}

$employee_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

// Count total leaves applied
$res = mysqli_query($conn, "SELECT 
    COUNT(*) AS total, 
    SUM(status='Approved') AS approved, 
    SUM(status='Rejected') AS rejected,
    SUM(status='Pending') AS pending 
    FROM leaves WHERE employee_id = $employee_id");
$data = mysqli_fetch_assoc($res);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .dashboard-container {
            max-width: 700px;
            margin: 60px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .nav-link {
            padding: 0.5rem 1rem;
        }
        h2 {
            margin-bottom: 20px;
        }
        .badge {
            font-size: 1rem;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <h2 class="text-primary">Welcome, <?= htmlspecialchars($name) ?> 👋</h2>

    <div class="mb-4">
        <h5 class="text-secondary">Leave Summary</h5>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
                <span>Total Leaves Applied:</span>
                <span class="badge bg-dark"><?= $data['total'] ?? 0 ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Approved:</span>
                <span class="badge bg-success"><?= $data['approved'] ?? 0 ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Rejected:</span>
                <span class="badge bg-danger"><?= $data['rejected'] ?? 0 ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
                <span>Pending:</span>
                <span class="badge bg-warning text-dark"><?= $data['pending'] ?? 0 ?></span>
            </li>
        </ul>
    </div>

    <div class="list-group">
        <a href="profile.php" class="list-group-item list-group-item-action">Update Profile</a>
        <a href="change_password.php" class="list-group-item list-group-item-action">Change Password</a>
        <a href="leave.php" class="list-group-item list-group-item-action">Apply for Leave</a>
        <a href="salary.php" class="list-group-item list-group-item-action">Salary History</a>
        <a href="../logout.php" class="list-group-item list-group-item-action text-danger">Logout</a>
    </div>
</div>

</body>
</html>

