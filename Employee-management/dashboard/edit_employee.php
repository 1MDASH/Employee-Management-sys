<?php
include 'db.php';
$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $address = $_POST['address'];

    $conn->query("UPDATE employees SET name='$name', email='$email', age='$age', address='$address' WHERE id=$id");
    header("Location: manage_employees.php");
}

$employee = $conn->query("SELECT * FROM employees WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Employee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Employee Management System</a>
            <div class="d-flex">
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
                    <a href="manage_students.php" class="active">Manage Employees</a>
                    <a href="attendance.php">Attendance</a>
                    <a href="grades.php">Performance</a>
                    <a href="import_export.php">Import/Export</a>
                    <a href="user_roles.php">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card shadow-sm p-4">
                            <h2 class="mb-4 text-center">Edit Employee</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" class="form-control" value="<?= htmlspecialchars($employee['name']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" class="form-control" value="<?= htmlspecialchars($employee['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input name="age" type="number" class="form-control" value="<?= htmlspecialchars($employee['age']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input name="address" class="form-control" value="<?= htmlspecialchars($employee['address']) ?>" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">Update</button>
                            </form>
                            <a href="manage_employees.php" class="btn btn-secondary mt-3 w-100">Back</a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
