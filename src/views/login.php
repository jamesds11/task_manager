<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log in | Task Manager</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body class="auth-page">
  <main class="auth-card">
    <p class="eyebrow">Welcome back</p>
    <h1>Log in to Task Manager</h1>
    <p class="auth-intro">Pick up right where you left off.</p>
    <?php
    if (isset($_SESSION["error"])) {
        echo "<p class='form-error' role='alert'>" . htmlspecialchars($_SESSION["error"], ENT_QUOTES, 'UTF-8') . "</p>";
        unset($_SESSION["error"]);
    }
    ?>
    <form class="auth-form" action="../actions/login_action.php" method="POST">
      <label for="username">Username</label>
      <input id="username" type="text" name="username" autocomplete="username" required>
      <label for="password">Password</label>
      <input id="password" type="password" name="password" autocomplete="current-password" required>
      <button class="primary-button" type="submit">Log in</button>
    </form>
    <p class="auth-switch">New here?</p>
    <form action="../actions/goRegister_action.php" method="GET">
      <button class="secondary-button" type="submit">Create an account</button>
    </form>
  </main>
</body>
</html>
