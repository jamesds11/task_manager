<?php
error_reporting(-1);
session_start();

if(!isset($_SESSION["logged_in"])){
  header("Location: ../views/login.php");
  exit();
}

if (!isset($_SESSION["csrf_token"])) {
  $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION["csrf_token"];

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

<body class="app-page">
  <!-- Your visible elements -->
   <nav class="site-nav">
    <span class="nav-user">Welcome, <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></span>
    <a href="./actions/logout_action.php">Logout</a>
  </nav>

    <header class="page-heading">
      <p class="eyebrow">Stay organized</p>
      <h1>Task Manager</h1>
      <p>Keep track of what matters and check it off as you go.</p>
    </header>

    <form method="GET" class="task-controls">
      <input type="checkbox" class="toggle-switch" id="sort-by-date" name="sort_by_date" value="1" <?= isset($_GET['sort_by_date']) ? 'checked' : '' ?>>
      <label for="sort-by-date">Sort by date</label>
      <input type="checkbox" class="toggle-switch" id="hide-completed" name="hide_completed" value="1" <?= isset($_GET['hide_completed']) ? 'checked' : '' ?>>
      <label for="hide-completed">Hide completed tasks</label>
      <button class="pretty-task" type="submit">Apply</button>
    </form>


    <ul class = "tasklist" id="tasklist"> <!--List-->
      <?php

    $hideCompleted = isset($_GET['hide_completed']);
    $sortByDate = isset($_GET['sort_by_date']);
    $sql = "SELECT * FROM tasks WHERE user_id = ?";
    if ($hideCompleted) {
      $sql .= " AND done = 0";
    }
    $sql .= $sortByDate ? " ORDER BY date ASC, id ASC" : " ORDER BY id ASC";

    $stmt = $conn->prepare($sql) or die($conn->error);
    $stmt->bind_param("i", $_SESSION["id"]) or die($stmt->error);
    $stmt->execute() or die($stmt->error);

    $result = $stmt->get_result() or die($stmt->error);

      while ($row = $result->fetch_assoc()){

        if($row["text"] != ""){
          $taskId = (int) $row['id'];
          $taskText = htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8');
          $date = new DateTime($row["date"]);
          $datePretty = $date->format('m/d/y');
          if($row["done"] == 0){
            echo 
            "<li class = 'task'>
            <div class = 'task-description'>
              <form action = './actions/update_action.php' method = 'POST'>
                  <button type='submit' class ='material-icon task-done checkbox-icon'>check</button>
                  <input type='hidden' name='taskID' value='$taskId'>
                  <input type='hidden' name='csrf_token' value='$csrfToken'>
                  <span class = 'task-checked'>$taskText</span>
              </form>
            </div>
            <div class = 'right'>
              <form action = './actions/delete_action.php' method = 'POST'>
                <span class = 'task-date'>$datePretty</span> 
                <button type='submit' class = 'task-delete material-icon' >delete</button>
                <input type='hidden' name='taskID' value='$taskId'>
                <input type='hidden' name='csrf_token' value='$csrfToken'>
              </form>
              </div>
            </li>";
          }
          else if($row["done"] == 1 )
            echo 
            "<li class = 'task'>
            <div class = 'task-description'>
              <form action = './actions/update_action.php' method = 'POST'>
                  <button type='submit' class ='material-icon task-done checkbox-icon'>check</button>
                  <input type='hidden' name='taskID' value='$taskId'>
                  <input type='hidden' name='csrf_token' value='$csrfToken'>
                  <span class = 'line'>$taskText</span>
              </form>
            </div>
            <div class = 'right'>
              <form action = './actions/delete_action.php' method = 'POST'>
                <span class = 'task-date'>$datePretty</span> 
                <button type = 'submit' class = 'task-delete material-icon' >delete</button>
                <input type='hidden' name='taskID' value='$taskId'>
                <input type='hidden' name='csrf_token' value='$csrfToken'>
              </form> 
            </div>
            </li>";
        }
      }

      ?>
    </ul>

    <form class = "form-structure" action="./actions/create_action.php" method = "POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">
      <input class ="form-length" type = "text" name = "description" required maxlength="1000"/>
      <br/>
      <input type = "date" name = "date" required/>
      <br/>
      <button class="pretty-task" >Create Task</button>
    </form>


</body>

</html>
