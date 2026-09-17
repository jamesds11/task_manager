<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['logged_in'], $_SESSION['id'], $_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
	http_response_code(403);
	exit('Forbidden');
}
// ./actions/register_action.php

// Read variables and create connection
$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");
$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$taskID = filter_input(INPUT_POST, 'taskID', FILTER_VALIDATE_INT);
$userId = $_SESSION["id"];
if ($taskID === false || $taskID === null) {
	http_response_code(422);
	exit('Invalid task ID.');
}

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND id = ?") or die($stmt->error);
$stmt->bind_param("ii", $userId, $taskID) or die($stmt->error);
        $stmt->execute() or die($stmt->error);

        $result = $stmt->get_result() or die($stmt->error);
$row = $result->fetch_assoc();

if (!$row) {
	http_response_code(404);
	exit('Task not found.');
}

if ($row["done"] == 0){
	$setID = 1;
}
else if($row["done"] != 0){
	$setID = 0;
}


$stmt = $conn->prepare("UPDATE tasks SET done = ? WHERE id = ? AND user_id = ?") or die($conn->error);
	$stmt->bind_param("iii", $setID, $taskID, $userId) or die($stmt->error);
	$stmt->execute() or die($stmt->error);

	header("Location: ../index.php");
	
?>
