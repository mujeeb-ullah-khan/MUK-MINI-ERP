<?php
require "../auth/auth_check.php";
require "../config/db.php";

$customers = $conn->query("SELECT * FROM customers ORDER BY name ASC");

if (isset($_GET['download'])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=customers_report.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID','Name','Phone','Email','Address']);
  while ($row = $customers->fetch_assoc()) {
    fputcsv($out, [$row['id'],$row['name'],$row['phone'],$row['email'],$row['address']]);
  }
  fclose($out);
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Customers Report</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <h2>Reports</h2> <nav class="top-nav"> <a href="index.php" class="back-btn">← Back</a> </nav>
</head>
<body>
<h2>Customers Report</h2>
<a href="customers_report.php?download=1" class="btn">Download CSV</a>
<table>
  <tr><th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Address</th></tr>
  <?php while($row = $customers->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['phone'] ?></td>
      <td><?= $row['email'] ?></td>
      <td><?= $row['address'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
