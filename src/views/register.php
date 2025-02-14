<?php
session_start();
?>

<p>Register</p>
<form action="../actions/register_action.php" method = "POST">
    <label for="username">Username:</label>
      <input type = "text" name = "username" required/>
      <br/>
    <label for="password">Password:</label>
      <input type = "text" name = "password" required/>
      <br/>
    <label for="confirm">Confirm Password:</label>
      <input type = "text" name = "confirm" required/>
      <br/>
      <button>Register</button>
</form>

<?php
if (isset($_SESSION["error"])) {
    echo "<p>" . $_SESSION["error"] . "</p>";
    unset($_SESSION["error"]);
}
?>
