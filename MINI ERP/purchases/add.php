<?php
require "../auth/auth_check.php";
require "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = $_POST['product_id'];
  $quantity   = (int)$_POST['quantity'];
  $price      = (float)$_POST['price'];

  if ($quantity > 0 && $price > 0) {
    $total = $price * $quantity;

    // Insert purchase record
    $insert = $conn->prepare("INSERT INTO purchases (product_id, user_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("iiidd", $product_id, $_SESSION['user_id'], $quantity, $price, $total);
    $insert->execute();

    // Update product stock
    $update = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity + ? WHERE id=?");
    $update->bind_param("ii", $quantity, $product_id);
    $update->execute();

    $success = "Purchase recorded successfully!";
  } else {
    $error = "Invalid quantity or price.";
  }
}

$products = $conn->query("SELECT id, name FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Record Purchase</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<header>
  <nav class="top-nav">
    <a href="index.php" class="btn back-btn">← Back</a>
  </nav>
</header>

<div class="main-content">
  <div class="form-card">
    <h2>Record a Purchase</h2>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <label>Product</label>
      <select name="product_id" required>
        <option value="">-- Select Product --</option>
        <?php while ($p = $products->fetch_assoc()): ?>
          <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endwhile; ?>
      </select>

      <label>Quantity</label>
      <input type="number" name="quantity" min="1" required>

      <label>Price</label>
      <input type="number" step="0.01" name="price" required>

      <button type="submit" class="btn">Record Purchase</button>
    </form>
  </div>
</div>
</body>
</html>
