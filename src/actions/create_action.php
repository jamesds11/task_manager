<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['logged_in'], $_SESSION['id'], $_SESSION['csrf_token'], $_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
	http_response_code(403);
	exit('Forbidden');
}
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

$description = trim($_POST["description"] ?? '');
$date = $_POST["date"] ?? '';
$user_id = $_SESSION["id"];
$done = 0;

$dateObject = DateTime::createFromFormat('Y-m-d', $date);
if ($description === '' || strlen($description) > 1000 || !$dateObject || $dateObject->format('Y-m-d') !== $date) {
	http_response_code(422);
	exit('Invalid task details.');
}

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
