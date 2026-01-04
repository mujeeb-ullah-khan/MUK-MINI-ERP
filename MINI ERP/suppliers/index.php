<?php
require "../auth/auth_check.php";
require "../config/db.php";

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Suppliers</title>
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
  <li><a href="index.php"><i class="fa-solid fa-building"></i><span> Suppliers</span></a></li>
  <li><a href="../customers/index.php"><i class="fa-solid fa-users"></i><span> Customers</span></a></li>
  <li><a href="../reports/index.php"><i class="fas fa-chart-bar"></i><span> Reports</span></a></li>
  <li><a href="../profit/index.php"><i class="fa-solid fa-chart-line"></i><span> Profit</span></a></li>
  <li><a href="../pages/contact.php"><i class="fas fa-envelope"></i><span> Contact</span></a></li>
  <li><a href="../pages/profile.php"><i class="fa-solid fa-user"></i><span> Profile</span></a></li>
  <li><a href="../auth/logout.php"><i class="fas fa-sign-out-alt"></i><span> Logout</span></a></li>
</ul>

</div>

<div class="main-content">
  <h2>Suppliers</h2>
  <nav class="top-nav">
    <a href="../products/index.php" class="btn back-btn">← Back</a>
  </nav>

  <a href="add.php" class="btn">+ Add Supplier</a>
  <table>
    <tr>
      <th>ID</th><th>Name</th><th>Contact</th><th>Phone</th><th>Email</th><th>Address</th><th>Actions</th>
    </tr>
    <?php while ($s = $suppliers->fetch_assoc()): ?>
      <tr>
        <td><?= $s['id'] ?></td>
        <td><?= htmlspecialchars($s['name']) ?></td>
        <td><?= htmlspecialchars($s['contact']) ?></td>
        <td><?= htmlspecialchars($s['phone']) ?></td>
        <td><?= htmlspecialchars($s['email']) ?></td>
        <td><?= htmlspecialchars($s['address']) ?></td>
        <td>
        
          <a href="delete.php?id=<?= $s['id'] ?>" class="delete-btn" onclick="return confirm('Delete this supplier?')">🗑️ Delete</a>
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
