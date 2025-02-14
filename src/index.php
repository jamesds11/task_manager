<?php
error_reporting(-1);
session_start();
if(!isset($_SESSION["logged_in"])){
  header("Location: ../views/login.php");
  exit();
}
?>

<p>You can also use normal tags outside of any PHP blocks.</p>

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

<body onload="readTasks()">
  <!-- Your visible elements -->
   <nav> 
    <a href="https://help210.byucyber.net/">TA Help</a> 
    <a href="https://byu.edu">BYU Home Page</a>
    <a href="https://learningsuite.byu.edu">Grades</a>
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
    </ul>

    <form class = "form-structure" onsubmit="createTask(event)">
      <input class ="form-length" type = "text" name = "description" required/>
      <br/>
      <input type = "date" name = "date" required/>
      <br/>
      <button class="pretty-task" >Create Task</button>
    </form>

  <!-- Links to scripts -->

  <script src="js/script.js">
    window.addEventListener("DOMContentLoaded", readTasks())
  </script>
</body>

</html>