<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbaccounts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

$fullname = "Test User";
$email = "test@example.com";
$password = "plaintextpassword";  // No hashing here!

// Check if the password is actually being hashed
echo "Password before insertion: " . $password;

// Directly insert the plain password
$stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $fullname, $email, $password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Signup successful!";
} else {
    echo "Error during signup!";
}

$stmt->close();
$conn->close();
?>
