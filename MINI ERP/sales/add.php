<?php
require "../auth/auth_check.php";
require "../config/db.php";

$success = "";
$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = (int)$_POST['product_id'];
  $quantity   = (int)$_POST['quantity']; // sale quantity entered by user

  // Get product price
  $stmt = $conn->prepare("SELECT price FROM products WHERE id=?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result && $result->num_rows > 0 && $quantity > 0) {
    $product = $result->fetch_assoc();
    $price = (float)$product['price'];
    $total = $price * $quantity;

    // Insert sale record
    $insert = $conn->prepare("INSERT INTO sales (product_id, user_id, quantity, price, total, sale_date) 
                              VALUES (?, ?, ?, ?, ?, NOW())");
    $insert->bind_param("iiidd", $product_id, $_SESSION['user_id'], $quantity, $price, $total);
    if ($insert->execute()) {
      $success = "Sale recorded successfully!";
    } else {
      $error = "Failed to record sale: " . $conn->error;
    }
  } else {
    $error = "Invalid quantity or product.";
  }
}

// Get products for dropdown
$products = $conn->query("SELECT id, name FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Record Sale</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar" id="sidebar">
  <button onclick="toggleSidebar()" class="toggle-btn">☰</button>
  <ul>
    <li><a href="../products/index.php"><i class="fa-solid fa-home"></i><span> Dashboard</span></a></li>
    <li><a href="index.php"><i class="fa-solid fa-cash-register"></i><span> Sales</span></a></li>
    <li><a href="../reports/index.php"><i class="fa-solid fa-chart-bar"></i><span> Reports</span></a></li>
    <li><a href="../pages/profile.php"><i class="fa-solid fa-user"></i><span> Profile</span></a></li>
    <li><a href="../auth/logout.php"><i class="fa-solid fa-right-from-bracket"></i><span> Logout</span></a></li>
  </ul>
</div>

<div class="main-content">
  <div class="form-card">
    <nav class="top-nav"> <a href="index.php" class="btn back-btn">← Back</a> </nav>
    <h2>Record a Sale</h2>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="post">
      <label>Product</label>
      <select name="product_id" required>
        <option value="">-- Select Product --</option>
        <?php if ($products && $products->num_rows > 0): ?>
          <?php while ($p = $products->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
          <?php endwhile; ?>
        <?php endif; ?>
      </select>

      <label>Quantity</label>
      <input type="number" name="quantity" min="1" required>

      <button type="submit" class="btn">Record Sale</button>
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
