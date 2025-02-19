<?php
error_reporting(-1);
session_start();

if(!isset($_SESSION["logged_in"])){
  header("Location: ../views/login.php");
  exit();
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css">
  <!-- Add an appropriate title in this tag -->
  <title>Task Manager Website</title>
  <!-- Links to stylesheets -->
</head>

<body>
  <!-- Your visible elements -->
   <nav> 
    <a href="https://help210.byucyber.net/">TA Help</a> 
    <a href="https://byu.edu">BYU Home Page</a>
    <a href="https://learningsuite.byu.edu">Grades</a>
    <a href="./actions/logout_action.php">Logout</a>
    <form action ="./actions/logout_action.php" method = "POST"> 
      <button>Logout</button>
    </form>
   

  </nav>
    <h1>Task Manager 2.0</h1>

    <input type = "checkbox" class = "toggle-switch" id = "SBD" name = "Sort by date"> 
    <label for = "Sort by date"> Sort by date </label>
    <input type = "checkbox" class = "toggle-switch" id = "FCT" name = "Filter completed tasks">
    <label for = "Filter completed tasks"> Filter completed Tasks</label>


    <ul class = "tasklist" id="tasklist"> <!--List-->
      <?php

    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ?") or die($stmt->error);
    $stmt->bind_param("i", $_SESSION["id"]) or die($stmt->error);
    $stmt->execute() or die($stmt->error);

    $result = $stmt->get_result() or die($stmt->error);

    $id = 1;

      while ($result->num_rows > 0){

        $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND id = ?") or die($stmt->error);
        $stmt->bind_param("ii", $_SESSION["id"], $id) or die($stmt->error);
        $stmt->execute() or die($stmt->error);

        $result = $stmt->get_result() or die($stmt->error);
        $row = $result->fetch_assoc();

        if($row["text"] != ""){

        echo "<li class = 'task'> 
        <form action = './actions/update_action.php' method = 'GET'>
          <button type='submit' class ='material-icon task-done checkbox-icon'>check</button>
          <input type = 'hidden'
        <form> 
        <span class = 'task-description'>$row[text]</span> 
        <span class = 'task-date'>$row[date]</span> 
        <button class = 'task-delete material-icon' >delete</button> 
        </li>";
        }

        $id++;
      }
      ?>
    </ul>

    <form class = "form-structure" action="./actions/create_action.php" method = "POST">
      <input class ="form-length" type = "text" name = "description"/>
      <br/>
      <input type = "date" name = "date"/>
      <br/>
      <button class="pretty-task" >Create Task</button>
    </form>

</body>

</html>