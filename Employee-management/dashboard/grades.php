<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
include 'db.php';

// Handle add grade
if (isset($_POST['add_grade'])) {
    $employee_id = $_POST['employee_id'];
    $subject = $_POST['subject'];
    $grade = $_POST['grade'];
    $stmt = $conn->prepare("INSERT INTO grades (employee_id, subject, grade) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $employee_id, $subject, $grade);
    $stmt->execute();
    $success = "Grade added successfully.";
}
// Handle delete grade
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM grades WHERE id=$id");
    $success = "Grade deleted.";
}
// Handle edit grade
if (isset($_POST['edit_grade'])) {
    $id = $_POST['grade_id'];
    $employee_id = $_POST['employee_id'];
    $subject = $_POST['subject'];
    $grade = $_POST['grade'];
    $stmt = $conn->prepare("UPDATE grades SET employee_id=?, subject=?, grade=? WHERE id=?");
    $stmt->bind_param('issi', $employee_id, $subject, $grade, $id);
    $stmt->execute();
    $success = "Grade updated.";
}
// Fetch employees and grades
$employees = $conn->query("SELECT * FROM employees");
$grades = $conn->query("SELECT grades.*, employees.name FROM grades JOIN employees ON grades.employee_id = employees.id ORDER BY grades.date_recorded DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Performance</title>
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
                    <a href="manage_employees.php">Manage Employees</a>
                    <a href="attendance.php">Attendance</a>
                    <a href="grades.php" class="active">Performance</a>
                    <a href="import_export.php">Import/Export</a>
                    <a href="user_roles.php">User Roles</a>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-center">
                    <div class="card shadow-sm p-4 mb-4" style="max-width: 600px; width: 100%;">
                        <h2 class="mb-4 text-center">Performance</h2>
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"> <?= $success ?> </div>
                        <?php endif; ?>
                        <!-- Add Grade Form -->
                        <form class="row g-3 mb-4" method="POST">
                            <input type="hidden" name="add_grade" value="1">
                            <div class="col-12">
                                <label class="form-label">Employee</label>
                                <select name="employee_id" class="form-select" required>
                                    <option value="">Select Employee</option>
                                    <?php $employees->data_seek(0); while ($emp = $employees->fetch_assoc()): ?>
                                        <option value="<?= $emp['id'] ?>"><?= htmlspecialchars($emp['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Subject</label>
                                <input name="subject" class="form-control" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Grade</label>
                                <input name="grade" class="form-control" required>
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">Add</button>
                            </div>
                        </form>
                        <!-- Grades Table -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover bg-white">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Employee</th>
                                        <th>Subject</th>
                                        <th>Grade</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $grades->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['name']) ?></td>
                                        <td><?= htmlspecialchars($row['subject']) ?></td>
                                        <td><?= htmlspecialchars($row['grade']) ?></td>
                                        <td><?= htmlspecialchars($row['date_recorded']) ?></td>
                                        <td>
                                            <!-- Edit Grade Modal Trigger -->
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                                            <a href="grades.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?')">Delete</a>
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                                              <div class="modal-dialog">
                                                <div class="modal-content">
                                                  <form method="POST">
                                                    <div class="modal-header">
                                                      <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Performance</h5>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                      <input type="hidden" name="edit_grade" value="1">
                                                      <input type="hidden" name="grade_id" value="<?= $row['id'] ?>">
                                                      <div class="mb-3">
                                                        <label class="form-label">Employee</label>
                                                        <select name="employee_id" class="form-select" required>
                                                          <?php $employees->data_seek(0); while ($emp = $employees->fetch_assoc()): ?>
                                                            <option value="<?= $emp['id'] ?>" <?= $emp['id'] == $row['employee_id'] ? 'selected' : '' ?>><?= htmlspecialchars($emp['name']) ?></option>
                                                          <?php endwhile; ?>
                                                        </select>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label class="form-label">Subject</label>
                                                        <input name="subject" class="form-control" value="<?= htmlspecialchars($row['subject']) ?>" required>
                                                      </div>
                                                      <div class="mb-3">
                                                        <label class="form-label">Grade</label>
                                                        <input name="grade" class="form-control" value="<?= htmlspecialchars($row['grade']) ?>" required>
                                                      </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                      <button type="submit" class="btn btn-warning">Update</button>
                                                    </div>
                                                  </form>
                                                </div>
                                              </div>
                                            </div>
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