<?php session_start(); ?>


<p>Login</p>
<form action="../actions/login_action.php" method = "POST">
    <label for="username">Username:</label>
      <input type = "text" name = "username" required/>
      <br/>
      <label for="password">Password:</label>
      <input type = "text" name = "password" required/>
      <br/>
      <button>Login</button>
</form>

<?php
if (isset($_SESSION["error"])) {
    echo "<p>" . $_SESSION["error"] . "</p>";
    unset($_SESSION["error"]);
}
?>