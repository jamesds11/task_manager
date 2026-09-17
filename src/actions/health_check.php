<?php
// ./actions/health_check.php

// Read variables and create connection

$mysql_servername = getenv("MYSQL_SERVERNAME");
$mysql_user = getenv("MYSQL_USER");
$mysql_password = getenv("MYSQL_PASSWORD");
$mysql_database = getenv("MYSQL_DATABASE");

$conn = new mysqli($mysql_servername, $mysql_user, $mysql_password, $mysql_database);

// Check connection
if ($conn->connect_error) {
	http_response_code(503);
	exit("Database unavailable");
} else {
	echo "Database connection successful";
}

?>
