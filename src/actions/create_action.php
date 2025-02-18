<?php
session_start();
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

$description = $_POST["description"];
$date = $_POST["date"];
$user_id = $_SESSION["id"];
$done = 0;

$sql = "INSERT INTO tasks (user_id, text, date, done) Values (?, ?, ?, ?)";
$stmt = $conn->prepare($sql) or die($conn->error);
$stmt->bind_param("issi", $user_id, $description, $date, $done) or die($stmt->error);

if ($stmt->execute()) {//this entirely is from chatGPT
	$task_id = $stmt->insert_id; //sets id from auto increment
} else {
	echo "Error: " . $stmt->error;
}

header("Location: ../index.php");

$stmt->close();
$conn->close();

?>