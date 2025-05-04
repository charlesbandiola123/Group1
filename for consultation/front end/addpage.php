<?php
session_start();
require("connect.php");

$user_id = $_SESSION['user_id'];
$email = $_SESSION['email']; // fetch email from session
$title_id = $_POST['title_id'];

$response = ["status" => "error", "message" => "Unknown error."];

// Insert into tblQuizzes
$query1 = "INSERT INTO tblQuizzes (user_id, title_id) VALUES ('$user_id', '$title_id')";
$result1 = mysqli_query($connection, $query1);

if ($result1) {
    $quiz_id = mysqli_insert_id($connection);

    $terms = json_decode($_POST['terms'][0]);
    $definitions = json_decode($_POST['definitions'][0]);

    // Insert terms and definitions into tblQuizTermsAndDefinitions
    foreach ($terms as $index => $term) {
        $definition = $definitions[$index];

        $query2 = "INSERT INTO tblQuizTermsAndDefinitions (quiz_id, termss, definitionss) 
                   VALUES ('$quiz_id', '$term', '$definition')";
        $result2 = mysqli_query($connection, $query2);

        if (!$result2) {
            $response = ["status" => "error", "message" => "Failed to insert a term: " . mysqli_error($connection)];
            echo json_encode($response);
            exit();
        }
    }

    // Insert into tblProjects
    $query3 = "INSERT INTO tblProjects (title_id, email) VALUES ('$title_id', '$email')";
    $result3 = mysqli_query($connection, $query3);

    if (!$result3) {
        $response = ["status" => "error", "message" => "Failed to insert project: " . mysqli_error($connection)];
        echo json_encode($response);
        exit();
    }

    $project_id = mysqli_insert_id($connection); // Get the inserted project_id

    // Fetch terms/definitions from tblQuizTermsAndDefinitions using quiz_id
    $query4 = "SELECT termss, definitionss FROM tblQuizTermsAndDefinitions WHERE quiz_id = '$quiz_id'";
    $result4 = mysqli_query($connection, $query4);

    if ($result4) {
        while ($row = mysqli_fetch_assoc($result4)) {
            $termss = $row['termss'];
            $definitionss = $row['definitionss'];

            // Insert into tblProjectItems
            $query5 = "INSERT INTO tblProjectItems (project_id, termss, definitionss)
                       VALUES ('$project_id', '$termss', '$definitionss')";
            $result5 = mysqli_query($connection, $query5);

            if (!$result5) {
                $response = ["status" => "error", "message" => "Failed to insert project item: " . mysqli_error($connection)];
                echo json_encode($response);
                exit();
            }
        }
    } else {
        $response = ["status" => "error", "message" => "Failed to fetch terms: " . mysqli_error($connection)];
        echo json_encode($response);
        exit();
    }

    // Now insert into tblQuizItems by linking quiz_id and all project_item_ids
    $query6 = "SELECT project_item_id FROM tblProjectItems WHERE project_id = '$project_id'";
    $result6 = mysqli_query($connection, $query6);

    if ($result6) {
        while ($row = mysqli_fetch_assoc($result6)) {
            $project_item_id = $row['project_item_id'];

            $query7 = "INSERT INTO tblQuizItems (quiz_id, project_item_id) VALUES ('$quiz_id', '$project_item_id')";
            $result7 = mysqli_query($connection, $query7);

            if (!$result7) {
                $response = ["status" => "error", "message" => "Failed to insert quiz item: " . mysqli_error($connection)];
                echo json_encode($response);
                exit();
            }
        }
    } else {
        $response = ["status" => "error", "message" => "Failed to fetch project items: " . mysqli_error($connection)];
        echo json_encode($response);
        exit();
    }

    $response = ["status" => "success", "message" => "Database updated with quiz, project, and project items."];
} else {
    $response = ["status" => "error", "message" => "Failed to insert quiz: " . mysqli_error($connection)];
}

echo json_encode($response);
?>
