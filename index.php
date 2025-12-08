<?php require_once __DIR__.'/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Meret — Connect Farmers & Markets</title>
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

  <!-- Hero Section -->
  <header class="hero">
    <div class="hero-content">
      <h1>Meret</h1>
      <p>Empowering rural farmers, connecting agents and urban consumers for a smarter market.</p>
      <div class="hero-buttons">
        <a href="login.php" class="btn">Login</a>
        <a href="register.php" class="btn alt">Register</a>
        <a href="support.php" class="btn">Support</a>
      </div>
    </div>
  </header>

  <!-- About Meret -->
  <section class="about">
    <div class="container">
      <h2>Why Meret?</h2>
      <p>
        Meret was built to address the challenges Ethiopian farmers face, connecting them directly to markets and reducing inefficiencies in the supply chain.
      </p>

      <div class="cards-grid">
        <div class="card">
          <i class="fas fa-info-circle card-icon"></i>
          <h3>Market Information</h3>
          <p>Farmers get real-time price updates and market trends to make informed decisions.</p>
        </div>
        <div class="card">
          <i class="fas fa-truck card-icon"></i>
          <h3>Logistics Support</h3>
          <p>We facilitate transportation from rural farms to urban markets efficiently.</p>
        </div>
        <div class="card">
          <i class="fas fa-box-open card-icon"></i>
          <h3>Reduce Post-Harvest Loss</h3>
          <p>Our platform helps manage storage, inventory, and timely delivery to reduce spoilage.</p>
        </div>
        <div class="card">
          <i class="fas fa-users card-icon"></i>
          <h3>Farmer Organization</h3>
          <p>Encourages group coordination and collaboration to increase bargaining power.</p>
        </div>
        <div class="card">
          <i class="fas fa-hand-holding-usd card-icon"></i>
          <h3>Fair Pricing</h3>
          <p>Ensures farmers and consumers get fair prices with transparency in the supply chain.</p>
        </div>
        <div class="card">
          <i class="fas fa-chart-line card-icon"></i>
          <h3>Analytics & Insights</h3>
          <p>Agents and administrators can track produce flow and market trends to optimize operations.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    &copy; 2025 AAU — Built by Group 1
  </footer>

</body>
</html>
