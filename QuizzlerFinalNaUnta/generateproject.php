<?php
require("connect.php"); // assumes it defines $connection

session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Unauthorized: Not logged in."]);
    exit();
}

$email = $_SESSION['email'];
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['projectTitle']) || !isset($data['projectData'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid input data."]);
    exit();
}

$projectTitle = $data['projectTitle'];
$projectData = $data['projectData']; // Array of {term, definition}

$connection->begin_transaction();

try {
    // Generate project_id manually
    $result = $connection->query("SELECT MAX(project_id) AS max_id FROM tblprojects");
    $row = $result->fetch_assoc();
    $project_id = $row['max_id'] ? $row['max_id'] + 1 : 1;

    // Insert into tblprojects
    $stmt = $connection->prepare("INSERT INTO tblprojects (project_id, title_id, email) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $project_id, $projectTitle, $email);
    $stmt->execute();
    $stmt->close();

    // Prepare to insert into tblprojectitems
    $stmt = $connection->prepare("INSERT INTO tblprojectitems (project_item_id, project_id, termss, definitionss) VALUES (?, ?, ?, ?)");

    // Get current max project_item_id
    $result = $connection->query("SELECT MAX(project_item_id) AS max_item_id FROM tblprojectitems");
    $row = $result->fetch_assoc();
    $next_project_item_id = $row['max_item_id'] ? $row['max_item_id'] + 1 : 1;

    foreach ($projectData as $item) {
        $termss = $item['term'];
        $definitionss = $item['definition'];

        $stmt->bind_param("iiss", $next_project_item_id, $project_id, $termss, $definitionss);
        $stmt->execute();
        $next_project_item_id++;
    }

    $stmt->close();
    $connection->commit();

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    $connection->rollback();
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
}
?>
