<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Run query safely
$sales = $conn->query("
    SELECT s.id, s.product_id, s.user_id, s.quantity, s.price, s.total, s.sale_date,
           p.name AS product_name,
           u.username
    FROM sales s
    JOIN products p ON s.product_id = p.id
    JOIN users u ON s.user_id = u.id
    ORDER BY s.sale_date DESC
");

if (!$sales) {
    die("Query failed: " . $conn->error); // ✅ helpful error message if query fails
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sales</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="sidebar" id="sidebar">
  <button onclick="toggleSidebar()" class="toggle-btn">☰</button>
  <ul>
    <li><a href="../products/index.php"><i class="fas fa-home"></i><span> Dashboard</span></a></li>
    <li><a href="index.php"><i class="fa-solid fa-cash-register"></i><span> Sales</span></a></li>
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
  <h2>Sales Records</h2>
  <nav class="top-nav">
    <a href="../products/index.php" class="btn back-btn">← Back</a>
  </nav>
<!-- Add this after the greeting div -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="success-message" style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin:10px 0;">
        ✅ <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message" style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin:10px 0;">
        ❌ <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>
  <a href="add.php" class="btn">+ Record Sale</a>
  <table>
    <tr>
      <th>ID</th><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Date</th><th>Recorded By</th><th>Actions</th>
    </tr>
    <?php while ($s = $sales->fetch_assoc()): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['product_name']) ?></td>
        <td><?= $s['quantity'] ?></td>
        <td>$<?= number_format($s['price'], 2) ?></td>
        <td>$<?= number_format($s['total'], 2) ?></td>
        <td><?= $s['sale_date'] ?></td>
        <td><?= htmlspecialchars($s['username']) ?></td>
        <td>
          <a href="delete.php?id=<?= $s['id'] ?>" class="delete-btn" onclick="return confirm('Delete this sale?')">🗑️ Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
  document.querySelector(".main-content").classList.toggle("expanded");
}
</script>
</body>
</html>
