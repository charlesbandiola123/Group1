<?php
session_start();
include "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid email or password!'); window.location.href='login.php';</script>";
    }
}
?>
