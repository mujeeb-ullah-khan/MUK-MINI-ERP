<?php
require "../auth/auth_check.php";
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $contact = $_POST['contact'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $address = $_POST['address'];

  $stmt = $conn->prepare("INSERT INTO suppliers (name, contact, phone, email, address) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $contact, $phone, $email, $address);
  $stmt->execute();

  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Supplier</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .form-container {
      max-width: 500px;
      margin: 40px auto;
      background: #f9f9f9;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #3498db;
    }

    .form-container label {
      display: block;
      margin-bottom: 6px;
      font-weight: bold;
    }

    .form-container input,
    .form-container textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    .form-container button {
      width: 100%;
      background: #3498db;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      font-size: 16px;
      box-shadow: 0 3px #2980b9;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .form-container button:hover {
      background: #2980b9;
    }

    .top-nav {
      text-align: right;
      margin: 20px;
    }

    .back-btn {
      background: #3498db;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 3px #2980b9;
      transition: all 0.2s ease;
    }

    .back-btn:hover {
      background: #2980b9;
    }
  </style>
</head>
<body>
<div class="main-content">
  <nav class="top-nav">
    <a href="index.php" class="back-btn">← Back</a>
  </nav>

  <div class="form-container">
    <h2>Add Supplier</h2>
    <form method="post">
      <label>Name</label>
      <input type="text" name="name" required>

      <label>Contact</label>
      <input type="text" name="contact">

      <label>Phone</label>
      <input type="text" name="phone">

      <label>Email</label>
      <input type="email" name="email">

      <label>Address</label>
      <textarea name="address" rows="3"></textarea>

      <button type="submit">Save Supplier</button>
    </form>
  </div>
</div>
</body>
</html>
