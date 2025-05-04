<?php
session_start(); // Start session

if (isset($_SESSION['fullname'])) {
    echo "<h1>Welcome, " . htmlspecialchars($_SESSION['fullname']) . "!</h1>";
} else {
    echo "<h1>No user logged in.</h1>";
}
?>
