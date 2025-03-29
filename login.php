<?php
require("connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if the email and password exist in the database
    $stmt = $connection->prepare("SELECT fullname FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Redirect to success page if login is correct
        header("Location: success.php?message=Welcome, " . urlencode($row['fullname']));
        exit();
    } else {
        // Redirect back to login.html with an error message
        header("Location: login.html?error=" . urlencode("Account not existing"));
        exit();
    }

    $stmt->close();
}

$connection->close();
?>
