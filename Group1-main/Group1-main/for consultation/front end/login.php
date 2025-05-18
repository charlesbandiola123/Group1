<?php
require("connect.php");
session_start(); // Start the session

 // in login.php


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Retrieve the user by email
    $stmt = $connection->prepare("SELECT user_id, fullname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if email exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $row['user_id']; // Store user_id in session
            $_SESSION['fullname'] = $row['fullname']; // Optionally, store fullname
            $_SESSION['email'] = $email; // ✅ Set correctly after login is verified


            // Redirect to success page
            header("Location: success.php?message=Welcome, " . urlencode($row['fullname']));
            exit();
        } else {
            // Incorrect password
            header("Location: login.html?error=" . urlencode("Incorrect password"));
            exit();
        }
    } else {
        // Account not found
        header("Location: login.html?error=" . urlencode("Account not existing"));
        exit();
    }

    $stmt->close();
}

$connection->close();
?>
