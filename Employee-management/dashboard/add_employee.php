<?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $address = $_POST['address'];
    mysqli_query($conn, "INSERT INTO employees (name, email, age, address) VALUES ('$name', '$email', '$age', '$address')");
    header("Location: manage_employees.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>
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
                            <h2 class="mb-4 text-center">Add New Employee</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input name="name" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input name="email" type="email" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Age</label>
                                    <input name="age" type="number" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <input name="address" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Save</button>
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
</html>
