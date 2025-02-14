<?php
session_start();
// ./actions/logout_action.php

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

// TODO: Log the user out

if (isset($_SESSION["username"])) {
	$stmt = $conn->prepare("UPDATE user SET logged_in = 0 WHERE username = ?") or die($conn->error);
	$stmt->bind_param("s", $_SESSION["username"]) or die($stmt->error);
	$stmt->execute() or die($stmt->error);
	unset($_SESSION["username"]);
	}
	header ("Location: ../views/login.php");
	session_destroy();
die();



?>
