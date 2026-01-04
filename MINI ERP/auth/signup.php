<?php
session_start();
require "../config/db.php";
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $email    = trim($_POST['email']);
  $password = $_POST['password'];
  if ($username === "" || $email === "" || $password === "") {
    $errors[] = "All fields are required.";
  } else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hash);
    if ($stmt->execute()) {
      header("Location: login.php");
      exit;
    } else {
      $errors[] = "Signup failed. Username or email may already exist.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Signup</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <nav class="top-nav"> <a href="../index.php" class="btn back-btn">← Back</a> </nav>
</head>
<body>
<div class="split-container">
  <div class="left-panel">
    <h1>Join MUK Inventory Management System🚀</h1>
    <p>Manage stock like a pro. Signup takes less than a minute!</p>
    <footer>© 2025 Inventory System</footer>
  </div>
  <div class="right-panel">
    <h2>Create Account</h2>
    <?php foreach ($errors as $e): ?><p class="error"><?= $e ?></p><?php endforeach; ?>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Signup Now</button>
    </form>
    <p class="link">Already have an account? <a href="login.php">Login</a></p>
  </div>
</div>
</body>
</html>
