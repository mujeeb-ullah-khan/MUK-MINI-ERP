<?php
require "../auth/auth_check.php";
require "../config/db.php";

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sku = trim($_POST['sku']);
  $name = trim($_POST['name']);
  $category = trim($_POST['category']);
  $quantity = (int)$_POST['stock_quantity'];
  $reorder = (int)$_POST['reorder_level'];
  $price = (float)$_POST['price'];

  if ($sku === "" || $name === "") $errors[] = "SKU and Name are required.";
  if ($quantity < 0) $errors[] = "Quantity cannot be negative.";
  if ($price < 0) $errors[] = "Price cannot be negative.";

  if (!$errors) {
    $stmt = $conn->prepare("INSERT INTO products (sku, name, category, stock_quantity, reorder_level, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssidd", $sku, $name, $category, $quantity, $reorder, $price);
    if ($stmt->execute()) {
      header("Location: index.php");
      exit;
    } else {
      $errors[] = "Failed to add product. Possibly duplicate SKU.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Add Product</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
  <h2>Add Product</h2>
  <nav>
    <a href="index.php">← Back to Dashboard</a>
  </nav>
</header>

<div class="form-card">
  <?php foreach ($errors as $e): ?><p class="error"><?= htmlspecialchars($e) ?></p><?php endforeach; ?>
  <form method="post">
    <label>SKU</label>
    <input name="sku" required>

    <label>Name</label>
    <input name="name" required>

    <label>Category</label>
    <input name="category">

    <label>Quantity</label>
    <input type="number" name="quantity" value="0">

    <label>Reorder Level</label>
    <input type="number" name="reorder_level" value="5">

    <label>Price</label>
    <input type="number" step="0.01" name="price" value="0">

    <button type="submit" class="btn">Save</button>
  </form>
</div>
</body>
</html>
