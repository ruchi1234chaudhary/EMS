<?php
include('../includes/db.php');
include('../includes/auth.php');
redirectIfNotLoggedIn();

if (!isAdmin()) {
    echo "Access Denied.";
    exit;
}

// Fetch Counts
$totalEmployees = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM employees WHERE role='employee'"))['total'];
$totalDepartments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM departments"))['total'];
$totalLeaves = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM leaves"))['total'];
$pendingLeaves = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM leaves WHERE status='Pending'"))['total'];
$approvedLeaves = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM leaves WHERE status='Approved'"))['total'];
$rejectedLeaves = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM leaves WHERE status='Rejected'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            max-width: 900px;
            margin: 50px auto;
        }
        .card {
            margin-bottom: 20px;
        }
        .nav-links a {
            margin: 5px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Admin Dashboard</a>
        <div>
            <a href="profile.php" class="btn btn-outline-light btn-sm me-2">Profile</a>
            <a href="../logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="dashboard-container p-4">
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card text-bg-primary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Registered Employees</h5>
                    <p class="card-text fs-4"><?php echo $totalEmployees; ?></p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card text-bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Listed Departments</h5>
                    <p class="card-text fs-4"><?php echo $totalDepartments; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Leave Applications</h5>
                    <p class="card-text fs-4"><?php echo $totalLeaves; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning shadow">
                <div class="card-body">
                    <h5 class="card-title">Pending Leave Requests</h5>
                    <p class="card-text fs-4"><?php echo $pendingLeaves; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success shadow">
                <div class="card-body">
                    <h5 class="card-title">Approved Leave Requests</h5>
                    <p class="card-text fs-4"><?php echo $approvedLeaves; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-danger shadow">
                <div class="card-body">
                    <h5 class="card-title">Rejected Leave Requests</h5>
                    <p class="card-text fs-4"><?php echo $rejectedLeaves; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Links -->
    <div class="mt-5 nav-links text-center">
         <a href="profile.php" class="btn btn-outline-primary">Update Profile</a>
        <a href="manage_departments.php" class="btn btn-outline-primary">Manage Departments</a>
        <a href="manage_employees.php" class="btn btn-outline-primary">Manage Employees</a>
        <a href="manage_salary.php" class="btn btn-outline-primary">Manage Salary</a>
        <a href="manage_leaves.php" class="btn btn-outline-primary">Manage Leave Requests</a>
        <a href="manage_leave_types.php" class="btn btn-outline-primary">Manage Leave Types</a>
    </div>
</div>

</body>
</html>







