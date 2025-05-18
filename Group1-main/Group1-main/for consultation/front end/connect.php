<?php
$host = "localhost"; // or your host
$username = "root"; // or your database username
$password = ""; // or your database password
$dbname = "dbaccounts"; // make sure this is the correct database name

// Create connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
