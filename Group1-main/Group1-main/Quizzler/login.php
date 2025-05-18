<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        .header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 40px;
  background: linear-gradient(to right, #8D72E1, #6C4AB6);
  border-bottom: 2px solid white;
}

/* Logo container */
.logo-container {
  display: flex;
  align-items: center;
  gap: 10px;
}

/* Logo styling */
.logo {
  width: 80px;
  height: auto;
}

/* Title styling */
.title {
  font-size: 50px;
  font-family: fantasy;
  color: white;
  white-space: nowrap;
}
        /* Container for the login form */
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
<div class="header">
    <div class="logo-container">
      <img src="logo.ico" alt="Logo" class="logo" />
      <span class="title">QUIZZLER</span>
    </div>
  </div>
    <div class="container">
        <h2>Login</h2>
        <form action="authenticate.php" method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
