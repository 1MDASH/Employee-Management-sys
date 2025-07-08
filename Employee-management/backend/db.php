<?php
$conn = mysqli_connect("localhost", "root", "", "employee_ms");
if (!$conn) {
    die("DB error");
}
?>
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error){
    die("connection failed:" .$conn->connect_error);
}

?>
