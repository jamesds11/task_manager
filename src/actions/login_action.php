<?php
session_start();
// ./actions/login_action.php

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

// TODO: Log the user in

$username = $_POST["username"];
$password = $_POST["password"];

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?") or die($stmt->error);
$stmt->bind_param("s", $username) or die($stmt->error);
$stmt->execute() or die($stmt->error);

$result = $stmt->get_result() or die($stmt->error);

if ($result->num_rows == 0){
	$_SESSION["error"] = "Invalid username";
	header ("Location: ../views/login.php");
	exit();
}

$row = $result->fetch_assoc() or die($stmt->error);

if(!password_verify($password, $row["password"])){
	$_SESSION["error"] = "Incorrect password";
	header ("Location: ../views/login.php");
	exit();
}

session_regenerate_id(true);
$_SESSION["username"] = $row["username"];
$_SESSION["logged_in"] = 1;
$_SESSION["id"] = $row["id"];

header ("Location: ../index.php");

$stmt->close();
$conn->close();
?>
