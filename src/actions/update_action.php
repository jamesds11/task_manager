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
$setID = 0;

$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND id = ?") or die($stmt->error);
        $stmt->bind_param("ii", $_SESSION["id"], $taskID) or die($stmt->error);
        $stmt->execute() or die($stmt->error);

        $result = $stmt->get_result() or die($stmt->error);
        $row = $result->fetch_assoc();

if ($row["done"] == 0){
	$setID = 1;
}
else if($row["done"] != 0){
	$setID = 0;
}


$stmt = $conn->prepare("UPDATE tasks SET done = ? WHERE id = ?") or die($conn->error);
	$stmt->bind_param("ii", $setID, $taskID) or die($stmt->error);
	$stmt->execute() or die($stmt->error);

	header("Location: ../index.php");
	
?>