<?php
require "../auth/auth_check.php";
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Reports</title>
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    .reports-container {
      max-width: 700px;
      margin: 40px auto;
      padding: 20px;
    }
    .reports-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #3498db;
    }
    .report-card {
      background: #f9f9f9;
      padding: 20px;
      margin-bottom: 15px;
      border-radius: 8px;
      box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }
    .report-card h3 {
      margin: 0 0 10px;
      color: #3498db;
    }
    .report-card p {
      margin: 0 0 12px;
      color: #555;
    }
    .btn {
      background: #3498db;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 3px #2980b9;
      transition: all 0.2s ease;
      display: inline-block;
    }
    .btn:hover {
      background: #2980b9;
    }
    .top-nav {
      text-align: right;
      margin: 20px;
    }
    .back-btn {
      background: #3498db;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 3px #2980b9;
      transition: all 0.2s ease;
    }
    .back-btn:hover {
      background: #2980b9;
    }
  </style>
</head>
<body>
  <h1>ERP Reports</h1>
  <nav class="top-nav">
    <a href="../products/index.php" class="back-btn">← Back</a>
  </nav>


<div class="reports-container">
  <div class="report-card">
    <h3>📦 Daily Inventory Report</h3>
    <p>Includes all products with their current quantity, reorder level, and price.</p>
    <a href="reports_daily.php" class="btn">Download CSV</a>
  </div>

  <div class="report-card">
    <h3>💰 Sales Report</h3>
    <p>Summary of all sales transactions with totals and product breakdowns.</p>
    <a href="sales_report.php" class="btn">View Report</a>
  </div>

  <div class="report-card">
    <h3>🚚 Purchases Report</h3>
    <p>Summary of all purchase transactions with supplier details.</p>
    <a href="purchases_report.php" class="btn">View Report</a>
  </div>

  <div class="report-card">
    <h3>👥 Customers Report</h3>
    <p>Shows customer activity, sales per customer, and top buyers.</p>
    <a href="customers_report.php" class="btn">View Report</a>
  </div>

  <div class="report-card">
    <h3>🏢 Suppliers Report</h3>
    <p>Shows supplier activity, purchase totals, and supplier performance.</p>
    <a href="suppliers_report.php" class="btn">View Report</a>
  </div>
</div>
</body>
</html>
