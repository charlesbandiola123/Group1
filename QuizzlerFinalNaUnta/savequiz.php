<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require("connect.php");
header('Content-Type: application/json');

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get and decode JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['quizData']) || !isset($data['quizTitle'])) {
    echo json_encode(["status" => "error", "message" => "Invalid data."]);
    exit;
}

$quizTitle = mysqli_real_escape_string($connection, $data['quizTitle']);
$terms = $data['quizData']; // array of {term, definition}

// Step 1: Insert into tblQuizzes
$insertQuiz = "INSERT INTO tblquizzes (user_id, title_id) VALUES ('$user_id', '$quizTitle')";
if (!mysqli_query($connection, $insertQuiz)) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to insert into tblquizzes: " . mysqli_error($connection)
    ]);
    exit;
}
$quiz_id = mysqli_insert_id($connection);

// Step 2: Insert terms and definitions + quiz items
foreach ($terms as $pair) {
    if (!isset($pair['term']) || !isset($pair['definition'])) continue;

    $term = mysqli_real_escape_string($connection, $pair['term']);
    $definition = mysqli_real_escape_string($connection, $pair['definition']);

    $insertTerm = "INSERT INTO tblquiztermsanddefinitions (quiz_id, termss, definitionss)
                   VALUES ('$quiz_id', '$term', '$definition')";
    if (!mysqli_query($connection, $insertTerm)) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to insert into tblquiztermsanddefinitions: " . mysqli_error($connection)
        ]);
        exit;
    }

    $insertItem = "INSERT INTO tblquizitems (quiz_id) VALUES ('$quiz_id')";
    if (!mysqli_query($connection, $insertItem)) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to insert into tblquizitems: " . mysqli_error($connection)
        ]);
        exit;
    }
}

// Final success response
echo json_encode(["status" => "success", "message" => "Quiz saved successfully."]);
?>