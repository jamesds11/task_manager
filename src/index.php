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
  <style>
        .line {
            text-decoration: line-through;
        }

        .right {
          text-align: right;
        }
    </style>
</head>

<body>
  <!-- Your visible elements -->
   <nav> 
    <a href="https://help210.byucyber.net/">TA Help</a> 
    <a href="https://byu.edu">BYU Home Page</a>
    <a href="https://learningsuite.byu.edu">Grades</a>
    <a href="./actions/logout_action.php">Logout</a>
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

      while ($row = $result->fetch_assoc()){

        if($row["text"] != ""){
          if($row["done"] == 0){

            $dateUgly = $row["date"];
            $date = new DateTime($dateUgly);
            $datePretty = $date->format('m/d/y');


            echo 
            "<li class = 'task'>
            <div class = 'task-description'>
              <form action = './actions/update_action.php' method = 'GET'>
                  <button type='submit' class ='material-icon task-done checkbox-icon'>check</button>
                  <input type = 'hidden' id = 'taskID' name = 'taskID' value = '" . $row['id'] . "'>
                  <span class = 'task-checked'>$row[text]</span>
              </form>
            </div>
            <div class = 'right'>
              <form action = './actions/delete_action.php' method = 'GET'>
                <span class = 'task-date'>$datePretty</span> 
                <button type='submit' class = 'task-delete material-icon' >delete</button>
                <input type = 'hidden' id = 'taskID' name = 'taskID' value = '" . $row['id'] . "'>
              </form>
              </div>
            </li>";
          }
          else if($row["done"] == 1 )
            echo 
            "<li class = 'task'>
            <span class = 'task-description'>
              <form action = './actions/update_action.php' method = 'GET'>
                  <button type='submit' class ='material-icon task-done checkbox-icon'>check</button>
                  <input type = 'hidden' id = 'taskID' name = 'taskID' value = '" . $row['id'] . "'>
                  <span class = 'line'>$row[text]</span>
              </form>
            </span>
            <span class = 'right'>
              <form action = './actions/delete_action.php' method = 'GET'>
                <span class = 'task-date'>$datePretty</span> 
                <button type = 'submit' class = 'task-delete material-icon' >delete</button>
                <input type = 'hidden' id = 'taskID' name = 'taskID' value = '" . $row['id'] . "'>
              </form> 
            </span>
            </li>";
        }
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