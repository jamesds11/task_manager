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

// TODO: Register a new user

$username = $_POST["username"];
$password = $_POST["password"];
$logged_in = 1;

if ($_POST["password"] != $_POST["confirm"]) {
	$_SESSION["error"] = "Passwords do not match";
	header ("Location: ../views/register.php");
	die();
}

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?") or die($stmt->error);
$stmt->bind_param("s", $username) or die($stmt->error);
$stmt->execute() or die($stmt->error);

$result = $stmt->get_result() or die($stmt->error);

if ($result->num_rows > 0){
	$_SESSION["error"] = "Username already exists";
	header ("Location: ../views/register.php");
	die();
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO user (username, password, logged_in) Values (?, ?, ?)";
$stmt = $conn->prepare($sql) or die($conn->error);
$stmt->bind_param("ssi", $username, $hashedPassword, $logged_in) or die($stmt->error);

if ($stmt->execute()) {//this entirely is from chatGPT
	$user_id = $stmt->insert_id; //sets id from auto increment
} else {
	echo "Error: " . $stmt->error;
}

$stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();
$row =$result->fetch_assoc();

$_SESSION["username"] = $row["username"];
$_SESSION["password"] = $row["password"];
$_SESSION["logged_in"] = 1;
$_SESSION["id"] = $row["id"];

header("Location: ../index.php");


$stmt->close();
$conn->close();

?>
