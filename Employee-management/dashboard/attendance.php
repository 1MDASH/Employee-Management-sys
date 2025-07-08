<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';
$date = date('Y-m-d');
$success = '';

// Handle attendance submission
if (isset($_POST['mark_attendance'])) {
    foreach ($_POST['attendance'] as $employee_id => $status) {
        // Upsert attendance for today
        $stmt = $conn->prepare("INSERT INTO attendance (employee_id, date, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status=?");
        $stmt->bind_param('isss', $employee_id, $date, $status, $status);
        $stmt->execute();
    }
    $success = "Attendance marked for today.";
}
// Fetch all employees
$employees = $conn->query("SELECT * FROM employees");
// Fetch today's attendance
$attendance_today = [];
$res = $conn->query("SELECT * FROM attendance WHERE date='$date'");
while ($row = $res->fetch_assoc()) {
    $attendance_today[$row['employee_id']] = $row['status'];
}
// Fetch attendance history (last 7 days)
$history = $conn->query("SELECT a.*, e.name FROM attendance a JOIN employees e ON a.employee_id = e.id ORDER BY a.date DESC LIMIT 100");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
                    <a href="admin_dashboard.php">Dashboard</a>
                    <a href="manage_students.php">Manage Employees</a>
                    <a href="attendance.php" class="active">Attendance</a>
                    <a href="grades.php">Performance</a>
                    <a href="import_export.php">Import/Export</a>
                    <a href="user_roles.php">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-center">
                    <div class="card shadow-sm p-4 mb-4" style="max-width: 700px; width: 100%;">
                        <h2 class="mb-4 text-center">Attendance for <?= date('F j, Y') ?></h2>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"> <?= $success ?> </div>
                        <?php endif; ?>
                        <form method="POST">
                            <input type="hidden" name="mark_attendance" value="1">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover bg-white">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Employee</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $employees->data_seek(0); while ($emp = $employees->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($emp['name']) ?></td>
                                            <td>
                                                <select name="attendance[<?= $emp['id'] ?>]" class="form-select" required>
                                                    <option value="Present" <?= (isset($attendance_today[$emp['id']]) && $attendance_today[$emp['id']] == 'Present') ? 'selected' : '' ?>>Present</option>
                                                    <option value="Absent" <?= (isset($attendance_today[$emp['id']]) && $attendance_today[$emp['id']] == 'Absent') ? 'selected' : '' ?>>Absent</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Save Attendance</button>
                        </form>
                    </div>
                </div>
                <div class="card shadow-sm p-4 mb-4">
                    <h4 class="mb-3">Attendance History (Recent)</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bg-white">
                            <thead class="table-dark">
                                <tr>
                                    <th>Date</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $history->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['status']) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>