<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");

// CSV download
if (isset($_GET['download'])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=daily_inventory.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['SKU','Name','Category','stock_quantity','Reorder Level','Price']);
  while ($row = $products->fetch_assoc()) {
    fputcsv($out, [$row['sku'],$row['name'],$row['category'],$row['stock_quantity'],$row['reorder_level'],$row['price']]);
  }
  fclose($out);
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Daily Inventory Report</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <h2>Reports</h2> <nav class="top-nav"> <a href="index.php" class="back-btn">← Back</a> </nav>
</head>
<body>
<h2>Daily Inventory Report</h2>
<a href="reports_daily.php?download=1" class="btn">Download CSV</a>
<table>
  <tr><th>SKU</th><th>Name</th><th>Category</th><th>Qty</th><th>Reorder</th><th>Price</th></tr>
  <?php while($row = $products->fetch_assoc()): ?>
    <tr>
      <td><?= $row['sku'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['category'] ?></td>
      <td><?= $row['stock_quantity'] ?></td>
      <td><?= $row['reorder_level'] ?></td>
      <td>$<?= number_format($row['price'],2) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
