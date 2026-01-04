<?php
require "../auth/auth_check.php";
require "../config/db.php";

// Stats - CHANGED: quantity to stock_quantity
$stats = $conn->query("SELECT 
  COUNT(*) AS total_items,
  SUM(stock_quantity) AS total_units,          -- CHANGED
  SUM(stock_quantity * price) AS total_value   -- CHANGED
FROM products")->fetch_assoc();

// Sales & Purchases Totals
$sales_total = $conn->query("SELECT SUM(total) AS total_income FROM sales")->fetch_assoc()['total_income'] ?? 0;
$purchase_total = $conn->query("SELECT SUM(total) AS total_spent FROM purchases")->fetch_assoc()['total_spent'] ?? 0;

// Profit totals
$profit_total = $sales_total - $purchase_total;
$profit_margin = ($sales_total > 0) ? ($profit_total / $sales_total * 100) : 0;

// Monthly Sales
$sales_monthly = $conn->query("
  SELECT MONTH(sale_date) AS month, SUM(total) AS total
  FROM sales
  GROUP BY MONTH(sale_date)
  ORDER BY MONTH(sale_date)
");

// Monthly Purchases
$purchases_monthly = $conn->query("
  SELECT MONTH(purchase_date) AS month, SUM(total) AS total
  FROM purchases
  GROUP BY MONTH(purchase_date)
  ORDER BY MONTH(purchase_date)
");

// Prepare arrays for charts
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$sales_data = array_fill(1, 12, 0.0);
$purchases_data = array_fill(1, 12, 0.0);

if ($sales_monthly) {
  while ($r = $sales_monthly->fetch_assoc()) {
    $m = (int)$r['month'];
    if ($m >= 1 && $m <= 12) $sales_data[$m] = (float)$r['total'];
  }
}
if ($purchases_monthly) {
  while ($r = $purchases_monthly->fetch_assoc()) {
    $m = (int)$r['month'];
    if ($m >= 1 && $m <= 12) $purchases_data[$m] = (float)$r['total'];
  }
}

// Re-index arrays to 0-based for JSON
$sales_data_zero = array_values($sales_data);
$purchases_data_zero = array_values($purchases_data);

// Compute monthly profit array
$profit_data = [];
for ($i = 0; $i < 12; $i++) {
  $profit_data[$i] = round($sales_data_zero[$i] - $purchases_data_zero[$i], 2);
}

// Products
$res = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Inventory Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <style>
    /* Keep original layout behavior, only adjust chart sizing and floating button */
    .dashboard-summary {
      position: absolute;
      top: 20px;
      right: 20px;
      display: flex;
      gap: 10px;
      z-index: 10;
    }
    .dashboard-summary .card {
      background: #f4f4f4;
      padding: 10px;
      border-radius: 6px;
      box-shadow: 0 2px #ccc;
      width: 140px;
      text-align: center;
      font-size: 14px;
    }
    .dashboard-summary .card h3 {
      margin: 0 0 6px;
      font-size: 15px;
      color: #3498db;
    }
    .dashboard {
      display: flex;
      gap: 20px;
      margin-top: 80px;
    }
    .dashboard .card {
      background: #f4f4f4;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 3px #ccc;
      flex: 1;
      text-align: center;
    }
    .dashboard .card h3 {
      margin: 0 0 10px;
      color: #3498db;
    }

    /* Analytics: ensure readable height and contained cards so page scroll works normally */
    .analytics {
      margin: 40px auto;
      max-width: 900px;
    }
    .analytics .chart-wrap {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 3px #ccc;
      padding: 12px;
      margin-bottom: 18px;
    }
    .analytics canvas {
      display: block;
      width: 100% !important;
      height: 320px !important; /* fixed readable height */
    }

    .edit-btn {
      background: #f39c12;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 3px #d35400;
      transition: all 0.2s ease;
      display: inline-block;
    }
    .edit-btn:hover { background: #d35400; }
    .delete-btn {
      background: #e74c3c;
      color: white;
      padding: 6px 12px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: bold;
      box-shadow: 0 3px #c0392b;
      transition: all 0.2s ease;
      display: inline-block;
    }
    .delete-btn:hover { background: #c0392b; }

    /* Floating add button: keep fixed and above charts */
    .floating-btn {
      position: fixed;
      right: 24px;
      bottom: 24px;
      background: #3498db;
      color: #fff;
      padding: 10px 14px;
      border-radius: 8px;
      text-decoration: none;
      z-index: 9999; /* ensure visible above charts */
      box-shadow: 0 6px rgba(0,0,0,0.12);
    }

    /* Ensure main-content scrolls while sidebar stays accessible */
    .main-content {
      margin-left: 220px; /* keep original spacing */
      padding: 20px;
      position: relative;
    }

    /* Responsive tweaks */
    @media (max-width: 900px) {
      .dashboard { flex-direction: column; }
      .dashboard-summary { position: static; margin: 10px 0; }
      .main-content { margin-left: 0; padding: 12px; }
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <button onclick="toggleSidebar()" class="toggle-btn">☰</button>
  <ul>
    <li><a href="index.php"><i class="fas fa-home"></i><span> Dashboard</span></a></li>
    <li><a href="../sales/index.php"><i class="fa-solid fa-cash-register"></i><span> Sales</span></a></li>
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

<!-- Main content -->
<div class="main-content">
  <div class="greeting">
    <h2>Welcome, <span><?= htmlspecialchars($_SESSION['username']) ?></span> 👋</h2>
  </div>
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

<script>
// Auto-hide success/error messages after 2 seconds
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.success-message, .error-message');
    
    messages.forEach(function(message) {
        // Start fade out after 2 seconds
        setTimeout(function() {
            message.style.opacity = '0';
            message.style.transition = 'opacity 0.5s ease';
            
            // Remove from DOM after fade out completes
            setTimeout(function() {
                message.style.display = 'none';
            }, 500); // Wait for fade out transition
        }, 2000); // Show for 2 seconds
    });
});
</script>
  <div class="dashboard-summary">
    <div class="card">
      <h3>Total Sales</h3>
      <p>$<?= number_format($sales_total, 2) ?></p>
    </div>
    <div class="card">
      <h3>Total Purchases</h3>
      <p>$<?= number_format($purchase_total, 2) ?></p>
    </div>
    <div class="card">
      <h3>Profit</h3>
      <p style="font-weight:700; color:<?= $profit_total >= 0 ? '#2ecc71' : '#e74c3c' ?>;">
        $<?= number_format($profit_total, 2) ?>
      </p>
      <small style="display:block; margin-top:6px; color:#666;">Margin: <?= number_format($profit_margin, 2) ?>%</small>
    </div>
  </div>

  <div class="dashboard">
    <div class="card">
      <h3>Total Items</h3>
      <p><?= $stats['total_items'] ?? 0 ?></p>
    </div>
    <div class="card">
      <h3>Total Units</h3>
      <p><?= $stats['total_units'] ?? 0 ?></p>
    </div>
    <div class="card">
      <h3>Total Value</h3>
      <p>$<?= number_format($stats['total_value'] ?? 0, 2) ?></p>
    </div>
  </div>

  <h3>Product Inventory</h3>
  <table>
    <tr>
      <th>SKU</th><th>Name</th><th>Category</th><th>Qty</th><th>Reorder</th><th>Price</th><th>Actions</th>
    </tr>
    <?php while ($row = $res->fetch_assoc()): ?>
      <tr class="<?= ($row['stock_quantity'] <= $row['reorder_level']) ? 'low' : '' ?>">
        <td><?= htmlspecialchars($row['sku']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <!-- CHANGED: $row['quantity'] to $row['stock_quantity'] -->
        <td><?= (int)$row['stock_quantity'] ?></td>
        <td><?= (int)$row['reorder_level'] ?></td>
        <td>$<?= number_format($row['price'], 2) ?></td>
        <td class="actions">
          <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn edit-btn">✏️ Edit</a>
          <a href="delete.php?id=<?= (int)$row['id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this product?')">🗑️ Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <a href="add.php" class="floating-btn">+ Add Product</a>

  <!-- Analytics Dashboard moved to bottom -->
  <div class="analytics">
    <h3>Analytics Dashboard</h3>

    <div class="chart-wrap">
      <h4 style="margin:0 0 8px;">Monthly Sales</h4>
      <canvas id="salesChart"></canvas>
    </div>

    <div class="chart-wrap">
      <h4 style="margin:0 0 8px;">Sales vs Purchases (Monthly)</h4>
      <canvas id="profitChart"></canvas>
    </div>

    <div class="chart-wrap">
      <h4 style="margin:0 0 8px;">Monthly Profit</h4>
      <canvas id="monthlyProfitChart"></canvas>
    </div>
  </div>
</div>

<script>
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
  document.querySelector(".main-content").classList.toggle("expanded");
}
</script>

<!-- Load Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
/* Injected PHP data */
const months = <?= json_encode($months) ?>;
const salesData = <?= json_encode($sales_data_zero) ?>;
const purchasesData = <?= json_encode($purchases_data_zero) ?>;
const profitData = <?= json_encode($profit_data) ?>;

/* Common chart options */
const commonOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    y: { beginAtZero: true, ticks: { precision: 0 } }
  },
  plugins: {
    legend: { position: 'top' }
  }
};

/* Monthly Sales (Bar) */
const salesCanvas = document.getElementById('salesChart');
if (salesCanvas) {
  new Chart(salesCanvas.getContext('2d'), {
    type: 'bar',
    data: {
      labels: months,
      datasets: [{
        label: 'Sales ($)',
        data: salesData,
        backgroundColor: 'rgba(52,152,219,0.9)',
        borderColor: 'rgba(52,152,219,1)',
        borderWidth: 1
      }]
    },
    options: Object.assign({}, commonOptions, {
      plugins: { title: { display: true, text: 'Monthly Sales' }, legend: { display: false } }
    })
  });
}

/* Sales vs Purchases (Line) */
const profitCanvas = document.getElementById('profitChart');
if (profitCanvas) {
  new Chart(profitCanvas.getContext('2d'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Sales ($)',
          data: salesData,
          borderColor: 'rgba(46,204,113,1)',
          backgroundColor: 'rgba(46,204,113,0.12)',
          tension: 0.2,
          fill: true,
          pointRadius: 3
        },
        {
          label: 'Purchases ($)',
          data: purchasesData,
          borderColor: 'rgba(231,76,60,1)',
          backgroundColor: 'rgba(231,76,60,0.12)',
          tension: 0.2,
          fill: true,
          pointRadius: 3
        }
      ]
    },
    options: Object.assign({}, commonOptions, {
      plugins: { title: { display: true, text: 'Sales vs Purchases (Monthly)' } }
    })
  });
}

/* Monthly Profit (Line) */
const monthlyProfitCanvas = document.getElementById('monthlyProfitChart');
if (monthlyProfitCanvas) {
  new Chart(monthlyProfitCanvas.getContext('2d'), {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Profit ($)',
        data: profitData,
        borderColor: 'rgba(155,89,182,1)',
        backgroundColor: 'rgba(155,89,182,0.12)',
        tension: 0.2,
        fill: true,
        pointRadius: 3
      }]
    },
    options: Object.assign({}, commonOptions, {
      plugins: { title: { display: true, text: 'Monthly Profit' } }
    })
  });
}
</script>
</body>
</html>