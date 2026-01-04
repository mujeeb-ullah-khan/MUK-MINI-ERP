<?php
require "../auth/auth_check.php";
require "../config/db.php";

$errors = [];
$success = "";
$name = "";
$email = "";
$message = "";

// Pre-fill email if user is logged in (optional)
if (isset($_SESSION['username'])) {
  $uStmt = $conn->prepare("SELECT email, id FROM users WHERE username=?");
  $uStmt->bind_param("s", $_SESSION['username']);
  $uStmt->execute();
  $uRes = $uStmt->get_result();
  if ($uRes->num_rows === 1) {
    $u = $uRes->fetch_assoc();
    $email = $u['email'];
    $userId = (int)$u['id'];
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $message = trim($_POST['message']);

  if ($name === "") $errors[] = "Name is required.";
  if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
  if ($message === "") $errors[] = "Message cannot be empty.";

  if (!$errors) {
    $stmt = $conn->prepare("INSERT INTO contacts (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $uid = isset($userId) ? $userId : null;
    $stmt->bind_param("isss", $uid, $name, $email, $message);
    if ($stmt->execute()) {
      $success = "Thank you! Your message has been sent.";
      $name = $email = $message = ""; // reset
    } else {
      $errors[] = "Failed to send message. Please try again.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Simple styling for the contact page */
    body {
      margin: 0;
      padding: 20px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f5f7fa;
      min-height: 100vh;
    }
    
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 15px;
      border-bottom: 1px solid #ddd;
    }
    
    .page-title {
      margin: 0;
      color: #3498db;
      font-size: 28px;
    }
    
    .back-link {
      color: #3498db;
      text-decoration: none;
      padding: 8px 16px;
      border: 1px solid #3498db;
      border-radius: 4px;
      transition: all 0.3s;
    }
    
    .back-link:hover {
      background: #3498db;
      color: white;
    }
    
    .form-card {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .form-card h3 {
      margin-top: 0;
      color: #333;
      font-size: 22px;
    }
    
    .form-card p {
      color: #666;
      margin-bottom: 25px;
    }
    
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #444;
    }
    
    input, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
      box-sizing: border-box;
    }
    
    textarea {
      resize: vertical;
    }
    
    .btn {
      background: #3498db;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .btn:hover {
      background: #3498db;
    }
    
    .success {
      color: #2e7d32;
      background: #e8f5e9;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 20px;
    }
    
    .error {
      color: #c62828;
      background: #ffebee;
      padding: 10px;
      border-radius: 4px;
      margin-bottom: 10px;
    }
    
    /* If you want to keep it very minimal */
    .minimal-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .minimal-title {
      color: #3498db;
      margin-bottom: 10px;
    }
    
    .minimal-back {
      color: #666;
      text-decoration: none;
      font-size: 14px;
    }
    
    .minimal-back:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<!-- OPTION 1: Simple header with title and back button -->
<div class="page-header">
  <h1 class="page-title">Contact Us</h1>
  <a href="../products/index.php" class="back-link">← Back </a>
</div>

<!-- OPTION 2: Even more minimal (uncomment if you prefer this) -->
<!--
<div class="minimal-header">
  <h1 class="minimal-title">Contact Us</h1>
  <a href="../products/index.php" class="minimal-back">← Back to Products</a>
</div>
-->

<div class="form-card">
  <h3>We'd love to hear from you</h3>
  <p>Questions, feedback, or feature requests — send us a message.</p>

  <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
  <?php foreach ($errors as $e): ?><p class="error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>

  <form method="post">
    <label>Name</label>
    <input name="name" value="<?= htmlspecialchars($name) ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

    <label>Message</label>
    <textarea name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>

    <button type="submit" class="btn">Send Message</button>
  </form>
</div>

</body>
</html>