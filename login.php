<?php
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Retrieve the user by email
    $stmt = $connection->prepare("SELECT fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            // Password is correct
            header("Location: success.php?message=Welcome, " . urlencode($row['fullname']));
            exit();
        }
    }

    // Invalid credentials
    header("Location: login.html?error=" . urlencode("Account not existing"));
    exit();

    $stmt->close();
}

$connection->close();
?>
