<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbaccounts";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($fullname) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email already exists"]);
        exit;
    }

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Signup successful"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error during signup"]);
    }

    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        /* General reset for margin and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body and background styles */
        body {
            background: linear-gradient(to right, #8D72E1, #e0b7fc);
            text-align: center;
            font-family: Arial, sans-serif;
        }

        /* Container for the signup form */
        .container {
            background: white;
            padding: 30px 40px;
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        /* Input fields */
        input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #8D72E1;
            border-radius: 50px;
            font-size: 16px;
        }

        /* Button styles */
        button {
            padding: 12px 20px;
            border-radius: 50px;
            border: none;
            background-color: #8D72E1;
            color: white;
            font-size: 18px;
            cursor: pointer;
            width: 90%;
            margin-top: 20px;
        }

        button:hover {
            background-color: #6C4AB6;
        }

        h2 {
            font-size: 36px;
            color: #8D72E1;
            margin-bottom: 20px;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 600px) {
            .container {
                padding: 20px;
                margin: 60px auto;
                width: 90%;
            }

            h2 {
                font-size: 30px;
            }

            button {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Sign up</h2>
        <form action="signup.php" method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Signup</button>
        </form>
    </div>
</body>
</html>
