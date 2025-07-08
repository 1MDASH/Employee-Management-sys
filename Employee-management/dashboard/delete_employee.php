<?php
include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM employees WHERE id=$id");
header("Location: manage_employees.php");
?>
