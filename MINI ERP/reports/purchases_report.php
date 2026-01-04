<?php
require "../auth/auth_check.php";
require "../config/db.php";

$purchases = $conn->query("SELECT pu.*, p.name AS product_name, u.username, s.name AS supplier_name
                           FROM purchases pu
                           JOIN products p ON pu.product_id = p.id
                           JOIN users u ON pu.user_id = u.id
                           LEFT JOIN suppliers s ON pu.supplier_id = s.id
                           ORDER BY pu.purchase_date DESC");

if (isset($_GET['download'])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=purchases_report.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID','Product','Quantity','Price','Total','Date','Supplier','Recorded By']);
  while ($row = $purchases->fetch_assoc()) {
    fputcsv($out, [$row['id'],$row['product_name'],$row['quantity'],$row['price'],$row['total'],$row['purchase_date'],$row['supplier_name'],$row['username']]);
  }
  fclose($out);
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Purchases Report</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <h2>Reports</h2> <nav class="top-nav"> <a href="index.php" class="back-btn">← Back</a> </nav>
</head>
<body>
<h2>Purchases Report</h2>
<a href="purchases_report.php?download=1" class="btn">Download CSV</a>
<table>
  <tr><th>ID</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th><th>Date</th><th>Supplier</th><th>Recorded By</th></tr>
  <?php while($row = $purchases->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['product_name'] ?></td>
      <td><?= $row['quantity'] ?></td>
      <td>$<?= number_format($row['price'],2) ?></td>
      <td>$<?= number_format($row['total'],2) ?></td>
      <td><?= $row['purchase_date'] ?></td>
      <td><?= $row['supplier_name'] ?></td>
      <td><?= $row['username'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
