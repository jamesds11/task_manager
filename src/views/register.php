<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create account | Task Manager</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
  <main class="auth-card">
    <p class="eyebrow">Get started</p>
    <h1>Create your account</h1>
    <p class="auth-intro">A simple place for every task on your list.</p>
    <?php
    if (isset($_SESSION["error"])) {
        echo "<p class='form-error' role='alert'>" . htmlspecialchars($_SESSION["error"], ENT_QUOTES, 'UTF-8') . "</p>";
        unset($_SESSION["error"]);
    }
    ?>
    <form class="auth-form" action="../actions/register_action.php" method="POST">
      <label for="username">Username</label>
      <input id="username" type="text" name="username" autocomplete="username" required>
      <label for="password">Password</label>
      <input id="password" type="password" name="password" autocomplete="new-password" required>
      <label for="confirm">Confirm password</label>
      <input id="confirm" type="password" name="confirm" autocomplete="new-password" required>
      <button class="primary-button" type="submit">Create account</button>
    </form>
    <p class="auth-switch">Already have an account?</p>
    <form action="../actions/goLogin_action.php" method="GET">
      <button class="secondary-button" type="submit">Log in</button>
    </form>
  </main>
</body>
</html>
