<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';

// Bulk delete logic
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['bulk_delete'])) {
    $ids = $_POST['employee_ids'] ?? [];
    if (!empty($ids)) {
        $id_list = implode(',', array_map('intval', $ids));
        $conn->query("DELETE FROM employees WHERE id IN ($id_list)");
        $success = count($ids) . " employee(s) deleted.";
        header("Location: manage_employees.php?success=" . urlencode($success));
        exit;
    }
}
$success = isset($_GET['success']) ? $_GET['success'] : '';

// Pagination and search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$where = $search ? "WHERE name LIKE '%$search%' OR email LIKE '%$search%' OR address LIKE '%$search%'" : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;
$total_employees = $conn->query("SELECT COUNT(*) as count FROM employees $where")->fetch_assoc()['count'];
$total_pages = ceil($total_employees / $limit);
$res = $conn->query("SELECT * FROM employees $where LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Employees</title>
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
                    <a href="manage_employees.php" class="active">Manage Employees</a>
                    <a href="#">Attendance</a>
                    <a href="#">Performance</a>
                    <a href="#">Import/Export</a>
                    <a href="#">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>All Employees</h2>
                    <a href="add_employee.php" class="btn btn-success">+ Add Employee</a>
                </div>
                <?php if ($success): ?>
                    <div class="alert alert-success"> <?= htmlspecialchars($success) ?> </div>
                <?php endif; ?>
                <form class="mb-3" method="get" action="">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or address" value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form>
                <form method="post" onsubmit="return confirm('Delete selected employees?');">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover bg-white">
                            <thead class="table-dark">
                                <tr>
                                    <th><input type="checkbox" id="select_all"></th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $res->fetch_assoc()) { ?>
                                <tr>
                                    <td><input type="checkbox" name="employee_ids[]" value="<?= $row['id'] ?>"></td>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td><?= htmlspecialchars($row['age']) ?></td>
                                    <td><?= htmlspecialchars($row['address']) ?></td>
                                    <td>
                                        <a href="edit_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_employee.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')">Delete</a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" name="bulk_delete" class="btn btn-danger mb-3">Delete Selected</button>
                </form>
                <!-- Pagination -->
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"> <?= $i ?> </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <a href="admin_dashboard.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Select/Deselect all checkboxes
        document.getElementById('select_all').onclick = function() {
            var checkboxes = document.getElementsByName('employee_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>
</body>
</html>
