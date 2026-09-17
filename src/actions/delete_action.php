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

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?") or die($conn->error);
	$stmt->bind_param("ii", $taskID, $userId) or die($stmt->error);
	$stmt->execute() or die($stmt->error);

	header("Location: ../index.php");

?>
