<?php
require "../auth/auth_check.php";
require "../config/db.php";

$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY name ASC");

// CSV download
if (isset($_GET['download'])) {
  header('Content-Type: text/csv');
  header('Content-Disposition: attachment;filename=suppliers_report.csv');
  $out = fopen('php://output', 'w');
  fputcsv($out, ['ID','Name','Contact','Phone','Email','Address']);
  while ($row = $suppliers->fetch_assoc()) {
    fputcsv($out, [
      $row['id'],
      $row['name'],
      $row['contact'],
      $row['phone'],
      $row['email'],
      $row['address']
    ]);
  }
  fclose($out);
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Suppliers Report</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <h2>Reports</h2> <nav class="top-nav"> <a href="index.php" class="back-btn">← Back</a> </nav>
</head>
<body>
<h2>Suppliers Report</h2>
<a href="suppliers_report.php?download=1" class="btn">Download CSV</a>
<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Contact</th>
    <th>Phone</th>
    <th>Email</th>
    <th>Address</th>
  </tr>
  <?php while($row = $suppliers->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['contact']) ?></td>
      <td><?= htmlspecialchars($row['phone']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td><?= htmlspecialchars($row['address']) ?></td>
    </tr>
  <?php endwhile; ?>
</table>
</body>
</html>
