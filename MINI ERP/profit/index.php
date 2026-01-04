<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Totals
$sales_total = $conn->query("SELECT SUM(total) AS total_income FROM sales")->fetch_assoc()['total_income'] ?? 0;
$purchases_total = $conn->query("SELECT SUM(total) AS total_spent FROM purchases")->fetch_assoc()['total_spent'] ?? 0;

$profit = $sales_total - $purchases_total;
$margin = ($sales_total > 0) ? ($profit / $sales_total * 100) : 0;

// Monthly Profit
$monthly = $conn->query("
  SELECT MONTH(s.sale_date) AS month, 
         SUM(s.total) - IFNULL((SELECT SUM(p.total) 
                                FROM purchases p 
                                WHERE MONTH(p.purchase_date)=MONTH(s.sale_date)),0) AS profit
  FROM sales s
  GROUP BY MONTH(s.sale_date)
  ORDER BY MONTH(s.sale_date)
");

$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$profit_data = array_fill(1, 12, 0.0);
if ($monthly) {
  while ($r = $monthly->fetch_assoc()) {
    $profit_data[(int)$r['month']] = (float)$r['profit'];
  }
}
$profit_data_zero = array_values($profit_data);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Profit Tracking</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    table { border-collapse: collapse; width: 400px; margin: 20px auto; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background: #3498db; color: white; }
    .analytics { max-width: 800px; margin: 40px auto; }
    canvas { background: #fff; border-radius: 8px; box-shadow: 0 3px #ccc; padding: 10px; }

    /* Back button aligned to the right */
    .top-nav {
      display: flex;
      justify-content: flex-end; /* ✅ pushes Back button to the right */
      padding: 10px 20px;
    }
    .back-btn {
      background:#3498db;
      color:#fff;
      padding:8px 14px;
      border-radius:6px;
      text-decoration:none;
      font-weight:bold;
      box-shadow:0 3px #2980b9;
      transition: all 0.2s ease;
    }
    .back-btn:hover { background:#2980b9; }
  </style>
</head>
<body>
  <!-- Top navigation with Back button on the right -->
  <div class="top-nav">
    <a href="../products/index.php" class="back-btn">← Back</a>
  </div>

  <h2 style="text-align:center;">Profit Tracking</h2>
  <table>
    <tr><th>Total Sales</th><td>$<?= number_format($sales_total,2) ?></td></tr>
    <tr><th>Total Purchases</th><td>$<?= number_format($purchases_total,2) ?></td></tr>
    <tr><th>Profit</th><td>$<?= number_format($profit,2) ?></td></tr>
    <tr><th>Profit Margin</th><td><?= number_format($margin,2) ?>%</td></tr>
  </table>

  <div class="analytics">
    <h3 style="text-align:center;">Monthly Profit</h3>
    <canvas id="profitChart"></canvas>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const months = <?= json_encode($months) ?>;
    const profitData = <?= json_encode($profit_data_zero) ?>;

    const ctx = document.getElementById('profitChart').getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: months,
        datasets: [{
          label: 'Profit ($)',
          data: profitData,
          borderColor: '#2ecc71',
          backgroundColor: 'rgba(46,204,113,0.2)',
          fill: true,
          tension: 0.2
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } },
        plugins: { title: { display: true, text: 'Monthly Profit Trend' } }
      }
    });
  </script>
</body>
</html>
