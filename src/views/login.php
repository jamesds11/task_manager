<?php session_start(); ?>


<h1>Login to Task Manager</h1>
<form action="../actions/login_action.php" method = "POST">
    <label for="username">Username:</label>
      <input type = "text" name = "username" required/>
      <br/>
      <label for="password">Password: </label>
      <input type = "text" name = "password" required/>
      <br/>
      <button>Login</button>
</form>

<h3>Need an account?</h3>
<form action = "../actions/goRegister_action.php" method = "GET">
    <button>Register</button>
</form>

<?php
if (isset($_SESSION["error"])) {
    echo "<p>" . $_SESSION["error"] . "</p>";
    unset($_SESSION["error"]);
}
?>