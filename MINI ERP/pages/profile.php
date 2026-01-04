<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Get user info
$stmt = $conn->prepare("SELECT username, email, created_at FROM users WHERE username=?");
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$success = "";
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newPass = $_POST['new_password'];
  $confirmPass = $_POST['confirm_password'];

  if ($newPass === "" || $confirmPass === "") {
    $errors[] = "Password fields cannot be empty.";
  } elseif ($newPass !== $confirmPass) {
    $errors[] = "Passwords do not match.";
  } else {
    $hashed = password_hash($newPass, PASSWORD_DEFAULT);
    $update = $conn->prepare("UPDATE users SET password=? WHERE username=?");
    $update->bind_param("ss", $hashed, $_SESSION['username']);
    if ($update->execute()) {
      $success = "Password updated successfully!";
    } else {
      $errors[] = "Failed to update password.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>User Profile</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">

</head>
<body>

<div class="sidebar" id="sidebar">
  <button onclick="toggleSidebar()" class="toggle-btn">☰</button>
  <ul>
    <li><a href="../products/index.php"><i class="fas fa-home"></i><span> Dashboard</span></a></li>
    <li><a href="../sales/index.php"><i class="fa-solid fa-cash-register"></i><span> Sales</span></a></li>
    <li><a href="../purchases/index.php"><i class="fa-solid fa-truck"></i><span> Purchases</span></a></li>
    <li><a href="../suppliers/index.php"><i class="fa-solid fa-building"></i><span> Suppliers</span></a></li>
    <li><a href="../customers/index.php"><i class="fa-solid fa-users"></i><span> Customers</span></a></li>
    <li><a href="../reports/index.php"><i class="fas fa-chart-bar"></i><span> Reports</span></a></li>
    <li><a href="../profit/index.php"><i class="fa-solid fa-chart-line"></i><span> Profit</span></a></li>
    <li><a href="../pages/contact.php"><i class="fas fa-envelope"></i><span> Contact</span></a></li>
    <li><a href="../pages/profile.php"><i class="fa-solid fa-user"></i><span> Profile</span></a></li>
    <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i><span> Logout</span></a></li>
  </ul>
</div>

<div class="main-content">
  <div class="form-card">
    <h2><i class="fas fa-user-circle"></i> User Profile</h2>

    <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
    <p><strong>Member since:</strong> <?= htmlspecialchars($user['created_at']) ?></p>

    <hr>
    <h3>Change Password</h3>
    <?php foreach ($errors as $e): ?><p class="error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>

    <form method="post">
      <label>New Password</label>
      <input type="password" name="new_password" required>

      <label>Confirm Password</label>
      <input type="password" name="confirm_password" required>

      <button type="submit" class="btn">Update Password</button>
    </form>
  </div>
</div>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
  document.querySelector(".main-content").classList.toggle("expanded");
}
</script>
</body>
</html>
