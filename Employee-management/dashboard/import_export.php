<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';
$success = $error = '';

// Export logic
if (isset($_GET['export'])) {
    $type = $_GET['export'];
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $type . '.csv"');
    $output = fopen('php://output', 'w');
    if ($type == 'employees') {
        fputcsv($output, ['ID', 'Name', 'Email', 'Age', 'Address']);
        $res = $conn->query("SELECT * FROM employees");
        while ($row = $res->fetch_assoc()) {
            fputcsv($output, [$row['id'], $row['name'], $row['email'], $row['age'], $row['address']]);
        }
    } elseif ($type == 'grades') {
        fputcsv($output, ['ID', 'Employee ID', 'Subject', 'Grade', 'Date Recorded']);
        $res = $conn->query("SELECT * FROM grades");
        while ($row = $res->fetch_assoc()) {
            fputcsv($output, [$row['id'], $row['employee_id'], $row['subject'], $row['grade'], $row['date_recorded']]);
        }
    } elseif ($type == 'attendance') {
        fputcsv($output, ['ID', 'Employee ID', 'Date', 'Status']);
        $res = $conn->query("SELECT * FROM attendance");
        while ($row = $res->fetch_assoc()) {
            fputcsv($output, [$row['id'], $row['employee_id'], $row['date'], $row['status']]);
        }
    }
    fclose($output);
    exit;
}
// Import logic
if (isset($_POST['import_type']) && isset($_FILES['import_file'])) {
    $type = $_POST['import_type'];
    $file = $_FILES['import_file']['tmp_name'];
    if (($handle = fopen($file, 'r')) !== false) {
        $header = fgetcsv($handle); // skip header
        $rowCount = 0;
        while (($data = fgetcsv($handle)) !== false) {
            if ($type == 'employees' && count($data) >= 5) {
                $stmt = $conn->prepare("REPLACE INTO employees (id, name, email, age, address) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('issis', $data[0], $data[1], $data[2], $data[3], $data[4]);
                $stmt->execute();
                $rowCount++;
            } elseif ($type == 'grades' && count($data) >= 5) {
                $stmt = $conn->prepare("REPLACE INTO grades (id, employee_id, subject, grade, date_recorded) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('iisss', $data[0], $data[1], $data[2], $data[3], $data[4]);
                $stmt->execute();
                $rowCount++;
            } elseif ($type == 'attendance' && count($data) >= 4) {
                $stmt = $conn->prepare("REPLACE INTO attendance (id, employee_id, date, status) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('iiss', $data[0], $data[1], $data[2], $data[3]);
                $stmt->execute();
                $rowCount++;
            }
        }
        fclose($handle);
        $success = ucfirst($type) . " imported: $rowCount record(s).";
    } else {
        $error = "Failed to open file.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import/Export</title>
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
                    <a href="attendance.php">Attendance</a>
                    <a href="grades.php">Performance</a>
                    <a href="import_export.php" class="active">Import/Export</a>
                    <a href="user_roles.php">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-center">
                    <div class="card shadow-sm p-4 mb-4" style="max-width: 700px; width: 100%;">
                        <h2 class="mb-4 text-center">Import/Export</h2>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"> <?= $success ?> </div>
                        <?php endif; ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"> <?= $error ?> </div>
                        <?php endif; ?>
                        <div class="mb-4">
                            <h5>Export Data</h5>
                            <a href="?export=employees" class="btn btn-primary btn-sm me-2">Export Employees</a>
                            <a href="?export=grades" class="btn btn-primary btn-sm me-2">Export Performance</a>
                            <a href="?export=attendance" class="btn btn-primary btn-sm">Export Attendance</a>
                        </div>
                        <div class="mb-4">
                            <h5>Import Data</h5>
                            <form method="POST" enctype="multipart/form-data" class="mb-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-auto">
                                        <select name="import_type" class="form-select form-select-sm" required>
                                            <option value="">Select Type</option>
                                            <option value="employees">Employees</option>
                                            <option value="grades">Performance</option>
                                            <option value="attendance">Attendance</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <input type="file" name="import_file" class="form-control form-control-sm" required accept=".csv">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-success btn-sm">Import</button>
                                    </div>
                                </div>
                            </form>
                            <div class="form-text">CSV columns must match exported format for each type.</div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>