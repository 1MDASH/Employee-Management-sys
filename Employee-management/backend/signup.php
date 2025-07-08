<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $check = mysqli_query($conn, "SELECT * FROM employees WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "Email already exists!";
    } else {
        $sql = "INSERT INTO employees (name, email, password) VALUES ('$name', '$email', '$pass')";
        if (mysqli_query($conn, $sql)) {
            echo "Signup successful!";
        } else {
            echo "Error!";
        }
    }
}
?>
?>
