<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - EmployeeMS</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<header>
    <nav class="navbar">
        <div class="logo">EmployeeMS</div>
        <ul class="nav-links">
            <li><a href="dashboard.php" class="active">Dashboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
</header>

<section class="dashboard-section">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?> 👋</h1>
    <p>This is your employee management dashboard.</p>

    <div class="dashboard-cards">
        <div class="card">
            <h2>👔 Manage Employees</h2>
            <p>View, edit and delete employee records.</p>
        </div>
        <div class="card">
            <h2>🏢 Departments</h2>
            <p>Assign and manage department data.</p>
        </div>
        <div class="card">
            <h2>📊 Reports</h2>
            <p>Generate performance and attendance reports.</p>
        </div>
    </div>
</section>

<footer>
    <p>&copy; 2025 Employee Management System | All Rights Reserved</p>
</footer>

</body>
</html>
