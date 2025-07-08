<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';
// Restrict to Admins only
$admin_username = $_SESSION['admin'];
$admin = $conn->query("SELECT a.*, r.role FROM admin a JOIN roles r ON a.id = r.employee_id WHERE a.username='$admin_username'")->fetch_assoc();
if (!$admin || $admin['role'] !== 'Admin') {
    echo '<div class="alert alert-danger">Access denied. Only Admins can manage user roles.</div>';
    exit;
}
// Handle role update
if (isset($_POST['update_role'])) {
    $user_id = intval($_POST['user_id']);
    $role_id = intval($_POST['role_id']);
    $conn->query("UPDATE admin SET role_id=$role_id WHERE id=$user_id");
    $success = "User role updated.";
}
// Fetch all users and roles
$users = $conn->query("SELECT a.*, r.role, r.id as role_row_id FROM admin a JOIN roles r ON a.id = r.employee_id");
$roles = $conn->query("SELECT DISTINCT role FROM roles");
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Roles</title>
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
                    <a href="import_export.php">Import/Export</a>
                    <a href="user_roles.php" class="active">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-center">
                    <div class="card shadow-sm p-4 mb-4" style="max-width: 700px; width: 100%;">
                        <h2 class="mb-4 text-center">User Roles Management</h2>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"> <?= $success ?> </div>
                        <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover bg-white">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Change Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $users->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['username']) ?></td>
                                        <td><?= htmlspecialchars($user['role']) ?></td>
                                        <td>
                                            <form method="POST" class="d-flex align-items-center gap-2">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <select name="role_id" class="form-select form-select-sm" style="width:auto;display:inline-block;">
                                                    <?php $roles->data_seek(0); while ($role = $roles->fetch_assoc()): ?>
                                                        <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['role']) ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                                <button type="submit" name="update_role" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>