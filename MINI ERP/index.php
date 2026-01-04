<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MUK Mini ERP</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Splash Screen */
    .splash-screen {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: linear-gradient(to bottom right,  #3498db, #3498db);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      animation: fadeOut 2s ease 2.00s forwards;
    }
    .splash-logo {
      opacity: 0;
      animation: fadeIn 1.5s ease forwards;
      font-size: 36px;
      color: white;
      font-weight: bold;
      letter-spacing: 1px;
      text-align: center;
    }
    @keyframes fadeIn { 
      from { opacity: 0; transform: scale(0.8);} 
      to { opacity: 1; transform: scale(1);} 
    }
    @keyframes fadeOut { 
      to { opacity: 0; visibility: hidden;} 
    }
    .main-content { 
      opacity: 0; 
      animation: showContent 1s ease 3s forwards; 
    }
    @keyframes showContent { 
      to { opacity: 1;} 
    }

    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      margin: 0;
      background: #f8fafc;
      color: #333;
      min-height: 100vh;
    }

    /* Layout Container */
    .container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar (Left Empty Space - Can be used for navigation later) */
    .sidebar {
      width: 250px;
      background: linear-gradient(to bottom,  #3498db,  #3498db);
      color: white;
      padding: 30px 20px;
      display: flex;
      flex-direction: column;
      box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }

    .logo-area {
      text-align: center;
      margin-bottom: 40px;
      padding-bottom: 20px;
      border-bottom: 1px solid rgba(255,255,255,0.2);
    }

    .logo-area img {
      width: 60px;
      height: auto;
      margin-bottom: 10px;
    }

    .logo-area h1 {
      font-size: 22px;
      font-weight: 700;
    }

    .menu-placeholder {
      margin-top: 40px;
      text-align: center;
      color: rgba(255,255,255,0.7);
      font-size: 14px;
    }

    /* Main Content Area */
    .main-area {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* Header */
    header {
      background: white;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      border-bottom: 1px solid #eaeaea;
    }

    .header-left h2 {
      color:  #3498db;
      font-size: 24px;
      font-weight: 600;
    }

    .header-right {
      display: flex;
      align-items: center;
      gap: 25px;
    }

    

    .weather-info, .date-info {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #555;
      font-size: 14px;
    }

    .weather-info i, .date-info i {
      color: #2a5298;
    }

    /* Hero Section */
    .hero {
      flex: 1;
      padding: 50px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(42, 82, 152, 0.05));
    }

    .hero-content {
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
    }

    .hero h1 {
      font-size: 42px;
      color:  #3498db;
      margin-bottom: 20px;
      line-height: 1.2;
    }

    .hero p {
      font-size: 18px;
      color: #555;
      margin-bottom: 35px;
      max-width: 600px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.6;
    }

    .btn {
      display: inline-block;
      background: linear-gradient(to right,  #3498db, #3498db);
      color: white;
      padding: 14px 32px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 16px;
      box-shadow: 0 4px 12px rgba(30, 60, 114, 0.2);
      transition: all 0.3s;
      border: none;
      cursor: pointer;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(30, 60, 114, 0.3);
    }

    /* Features Section */
    .features {
      padding: 60px 40px;
      background: white;
      border-top: 1px solid #eaeaea;
    }

    .features h2 {
      text-align: center;
      color:  #3498db;
      margin-bottom: 40px;
      font-size: 32px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: #f8fafc;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      text-align: center;
      transition: transform 0.3s;
      border: 1px solid #eaeaea;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }

    .card i {
      font-size: 36px;
      color:  #3498db;
      margin-bottom: 20px;
    }

    .card h3 {
      margin-bottom: 15px;
      color:  #3498db;
      font-size: 22px;
    }

    .card p {
      color: #666;
      line-height: 1.5;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 25px;
      background: #3498db;
      color: rgba(255,255,255,0.8);
      font-size: 14px;
      border-top: 1px solid rgba(255,255,255,0.1);
    }

    /* Navigation */
    nav {
      margin-top: 30px;
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    nav a {
      color: #3498db;
      text-decoration: none;
      padding: 8px 16px;
      border-radius: 4px;
      transition: background 0.3s;
    }

    nav a:hover {
      background: rgba(255,255,255,0.1);
    }

    /* Responsive */
    @media (max-width: 1024px) {
      .container {
        flex-direction: column;
      }
      
      .sidebar {
        width: 100%;
        padding: 20px;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
      
      .logo-area {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
      }
      
      .menu-placeholder {
        margin-top: 0;
      }
    }

    @media (max-width: 768px) {
      .header-right {
        flex-direction: column;
        gap: 15px;
        align-items: flex-end;
      }
      
      .search-box input {
        width: 250px;
      }
      
      .hero h1 {
        font-size: 32px;
      }
      
      .hero p {
        font-size: 16px;
      }
      
      .features-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 600px) {
      .sidebar {
        flex-direction: column;
        text-align: center;
        gap: 20px;
      }
      
      header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
      }
      
      .header-right {
        align-items: center;
        width: 100%;
      }
      
      .search-box input {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<!-- Splash Screen -->
<div class="splash-screen">
  <div class="splash-logo">
    <img src="assets/img/logo.png" alt="Mini ERP Logo" style="width:80px; height:auto;"><br>
    MUK Mini ERP
  </div>
</div>

<!-- Main Landing Page -->
<div class="main-content">
  <div class="container">
    <!-- Sidebar (Left Panel) -->
    <div class="sidebar">
      <div class="logo-area">
        <img src="assets/img/logo.png" alt="Mini ERP Logo">
        <h1>MUK Mini ERP</h1>
      </div>
      <div>
        <p> Welcome</p>
        <p style="font-size: 12px; margin-top: 10px;"> Demo website| ERP  | Business</p>
      </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="main-area">
      <!-- Header -->
      <header>
        <div class="header-left">
          <h2>Mini ERP Dashboard</h2>
        </div>
        <div class="header-right">
          
          <div class="weather-info">
            <i class="fas fa-cloud-sun"></i>
            <span>7°C Light rain</span>
          </div>
          <div class="date-info">
            <i class="far fa-calendar-alt"></i>
            <span id="current-date">BKG Q2/01/2006</span>
          </div>
        </div>
      </header>
      
      <!-- Hero Section -->
      <section class="hero">
        <div class="hero-content">
          <h1>Welcome to MUK Mini ERP</h1>
          <p>Track products, manage stock, and generate reports with ease.</p>
          <a href="auth/signup.php" class="btn">Get Started</a>
          
          <!-- Navigation Links -->
          <nav>
            <a href="index.php"><i class="fas fa-home"></i> Home</a>
            <a href="pages/about.php"><i class="fas fa-info-circle"></i> About</a>
            <a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="auth/signup.php"><i class="fas fa-user-plus"></i> Signup</a>
          </nav>
        </div>
      </section>
      
      <!-- Features Section -->
      <section class="features">
        <h2>Core Features</h2>
        <div class="features-grid">
          <div class="card">
            <i class="fas fa-boxes"></i>
            <h3>📦 Manage Products</h3>
            <p>Add, edit, and track items with categories and reorder levels.</p>
          </div>
          <div class="card">
            <i class="fas fa-chart-line"></i>
            <h3>📊 Smart Dashboard</h3>
            <p>See totals, low-stock alerts, and charts at a glance.</p>
          </div>
          <div class="card">
            <i class="fas fa-file-export"></i>
            <h3>📑 Reports</h3>
            <p>Export daily inventory reports in CSV format.</p>
          </div>
        </div>
      </section>
      
      <!-- Footer -->
      <footer>
        <p>© 2025 MUK Mini ERP System. All rights reserved.</p>
      </footer>
    </div>
  </div>
</div>

<script>
  // Update current date
  document.addEventListener('DOMContentLoaded', function() {
    const now = new Date();
    const options = { 
      weekday: 'short', 
      year: 'numeric', 
      month: 'short', 
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    };
    const dateStr = now.toLocaleDateString('en-US', options);
    document.getElementById('current-date').textContent = dateStr;
  });
</script>

</body>
</html>