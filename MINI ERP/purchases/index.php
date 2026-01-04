<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Get purchases
$purchases = $conn->query("SELECT pu.*, p.name AS product_name, u.username 
                           FROM purchases pu 
                           JOIN products p ON pu.product_id = p.id 
                           JOIN users u ON pu.user_id = u.id 
                           ORDER BY pu.purchase_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Purchase Records</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <button onclick="toggleSidebar()" class="toggle-btn">☰</button>
  <ul>
  <li><a href="../products/index.php"><i class="fas fa-home"></i><span> Dashboard</span></a></li>
  <li><a href="../sales/index.php"><i class="fa-solid fa-cash-register"></i><span> Sales</span></a></li>
  <li><a href="index.php"><i class="fa-solid fa-truck"></i><span> Purchases</span></a></li>
  <li><a href="../suppliers/index.php"><i class="fa-solid fa-building"></i><span> Suppliers</span></a></li>
  <li><a href="../customers/index.php"><i class="fa-solid fa-users"></i><span> Customers</span></a></li>
  <li><a href="../reports/index.php"><i class="fas fa-chart-bar"></i><span> Reports</span></a></li>
  <li><a href="../profit/index.php"><i class="fa-solid fa-chart-line"></i><span> Profit</span></a></li>
  <li><a href="../pages/contact.php"><i class="fas fa-envelope"></i><span> Contact</span></a></li>
  <li><a href="../pages/profile.php"><i class="fa-solid fa-user"></i><span> Profile</span></a></li>
  <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i><span> Logout</span></a></li>
</ul>

</div>

<!-- Main content -->
<div class="main-content">

    <nav class="top-nav">
      <a href="../products/index.php" class="btn back-btn">← Back</a>
    </nav>
 

  <h2>Purchase Records</h2>
  <a href="add.php" class="btn">+ Record Purchase</a>

  <table>
    <tr>
      <th>ID</th><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th><th>Date</th><th>Recorded By</th><th>Actions</th>
    </tr>
    <?php while ($pu = $purchases->fetch_assoc()): ?>
      <tr>
        <td><?= $pu['id'] ?></td>
        <td><?= htmlspecialchars($pu['product_name']) ?></td>
        <td><?= $pu['quantity'] ?></td>
        <td>$<?= number_format($pu['price'], 2) ?></td>
        <td>$<?= number_format($pu['total'], 2) ?></td>
        <td><?= $pu['purchase_date'] ?></td>
        <td><?= htmlspecialchars($pu['username']) ?></td>
        <td>
          <a href="delete.php?id=<?= $pu['id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this purchase?')">🗑️ Delete</a>
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
