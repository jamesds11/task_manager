<?php
session_start();
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

$taskID = $_GET["taskID"];

$stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?") or die($conn->error);
	$stmt->bind_param("i", $taskID) or die($stmt->error);
	$stmt->execute() or die($stmt->error);

	header("Location: ../index.php");

?>