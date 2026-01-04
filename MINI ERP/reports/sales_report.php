<?php
require "../auth/auth_check.php";
require "../config/db.php";

$sales = $conn->query("SELECT s.*, p.name AS product_name, u.username 
                       FROM sales s 
                       JOIN products p ON s.product_id = p.id 
                       JOIN users u ON s.user_id = u.id 
                       ORDER BY s.sale_date DESC");

if (isset($_GET['download'])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=sales_report.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID','Product','Quantity','Price','Total','Date','Recorded By']);
  while ($row = $sales->fetch_assoc()) {
    fputcsv($out, [$row['id'],$row['product_name'],$row['quantity'],$row['price'],$row['total'],$row['sale_date'],$row['username']]);
  }
  fclose($out);
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Sales Report</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <h2>Sales Report</h2> <nav class="top-nav"> <a href="index.php" class="back-btn">← Back</a> </nav>
</head>
<body>
<h2>Sales Report</h2>
<a href="sales_report.php?download=1" class="btn">Download CSV</a>
<table>
  <tr><th>ID</th><th>Product</th><th>Qty</th><th>Price</th><th>Total</th><th>Date</th><th>Recorded By</th></tr>
  <?php while($row = $sales->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['product_name'] ?></td>
      <td><?= $row['quantity'] ?></td>
      <td>$<?= number_format($row['price'],2) ?></td>
      <td>$<?= number_format($row['total'],2) ?></td>
      <td><?= $row['sale_date'] ?></td>
      <td><?= $row['username'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
