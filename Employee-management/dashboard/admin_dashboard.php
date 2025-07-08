<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
// For summary cards, fetch total employees (placeholder for now)
include 'db.php';
$total_employees = $conn->query("SELECT COUNT(*) as count FROM employees");
$total_employees = $total_employees ? $total_employees->fetch_assoc()['count'] : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: #fff;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
        }
        .sidebar a.active, .sidebar a:hover {
            background: #2980b9;
        }
        /* Dashboard card sizing */
        .dashboard-card {
            min-height: 140px;
            padding: 18px 20px;
            border-radius: 12px;
            margin-bottom: 18px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: box-shadow 0.2s, transform 0.2s;
            cursor: pointer;
        }
        .dashboard-card:hover {
            box-shadow: 0 0 20px rgba(41,128,185,0.2);
            transform: translateY(-2px) scale(1.03);
        }
        .dashboard-card .card-title {
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .dashboard-card .card-text.display-6 {
            font-size: 2rem;
            font-weight: 600;
        }
        @media (max-width: 768px) {
            .dashboard-card {
                min-height: 100px;
                padding: 12px 10px;
            }
            .dashboard-card .card-text.display-6 {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Employee Management System</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Welcome, Admin</span>
                <a href="logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block sidebar py-4">
                <div class="position-sticky">
                    <a href="admin_dashboard.php" class="active">Dashboard</a>
                    <a href="manage_students.php">Manage Employees</a>
                    <a href="attendance.php">Attendance</a>
                    <a href="grades.php">Performance</a>
                    <a href="import_export.php">Import/Export</a>
                    <a href="user_roles.php">User Roles</a>
                    <!-- If you have performance.php -->
                    <a href="performance.php">Performance</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <h2 class="mb-4">Dashboard Overview</h2>
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <a href="manage_employees.php" style="text-decoration:none;">
                        <div class="card text-white bg-success dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Employees</h5>
                                <p class="card-text display-6"><?php echo $total_employees; ?></p>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="#" style="text-decoration:none; cursor:not-allowed;">
                        <div class="card text-white bg-info dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Recent Activity</h5>
                                <p class="card-text">(Coming soon)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="attendance.php" style="text-decoration:none;">
                        <div class="card text-white bg-warning dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Attendance</h5>
                                <p class="card-text">(Go to Attendance)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <a href="grades.php" style="text-decoration:none;">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Performance</h5>
                                <p class="card-text">(Go to Performance)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="import_export.php" style="text-decoration:none;">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Import/Export</h5>
                                <p class="card-text">(Go to Import/Export)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                    <div class="col-md-4 mb-3">
                        <a href="user_roles.php" style="text-decoration:none;">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">User Roles</h5>
                                <p class="card-text">(Go to User Roles)</p>
                            </div>
                        </div>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
