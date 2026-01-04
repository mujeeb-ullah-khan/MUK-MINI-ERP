<?php
session_start();
require "../config/db.php";
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      header("Location: ../products/index.php");
      exit;
    } else {
      $errors[] = "Invalid password.";
    }
  } else {
    $errors[] = "User not found.";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <nav class="top-nav"> <a href="../index.php" class="btn back-btn">← Back</a> </nav>
</head>
<body>
<div class="split-container">
  <div class="left-panel">
    <h1>Hello MUK Inventory Management system 👋</h1>
    <p>Track your products, automate reports, and stay ahead of stock issues.</p>
    <footer>© 2025 Inventory System</footer>
  </div>
  <div class="right-panel">
    <h2>Welcome Back!</h2>
    <?php foreach ($errors as $e): ?><p class="error"><?= $e ?></p><?php endforeach; ?>
    <form method="post">
      <input name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <button type="submit" class="btn">Login Now</button>
    </form>
    <p class="link">No account? <a href="signup.php">Create one</a></p>
  </div>
</div>
</body>
</html>
