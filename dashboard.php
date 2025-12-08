<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/auth.php';
checkLogin();
$role = getUserRole();
$name = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Meret — Fresh Marketplace Dashboard</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="dashboard.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search for fresh produce, farmers, or prices...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="pages/consumer/cart.php" class="cart-icon" title="Shopping Cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count">0</span>
    </a>
    <div class="user-profile">
      <span class="user-name"><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<nav class="navbar">
  <ul class="nav-list">
    <li><a href="dashboard.php" class="nav-link active">Home</a></li>
    <?php if ($role === 'consumer'): ?>
      <li><a href="pages/consumer/browse.php" class="nav-link">Browse Produce</a></li>
      <li><a href="pages/common/price_chart.php" class="nav-link">Market Prices</a></li>
      <li><a href="pages/consumer/reviews.php" class="nav-link">Reviews</a></li>
      <li><a href="pages/consumer/my_orders.php" class="nav-link">My Orders</a></li>
    <?php elseif ($role === 'farmer'): ?>
      <li><a href="pages/farmer/post_produce.php" class="nav-link">Post Produce</a></li>
      <li><a href="pages/farmer/order_track.php" class="nav-link">Orders</a></li>
      <li><a href="pages/common/submit_price.php" class="nav-link">Prices</a></li>
    <?php elseif ($role === 'agent'): ?>
      <li><a href="pages/agent/approve_listings.php" class="nav-link">Approvals</a></li>
      <li><a href="pages/agent/manage_center.php" class="nav-link">Inventory</a></li>
    <?php elseif ($role === 'admin'): ?>
      <li><a href="pages/admin/approve_agents.php" class="nav-link">Agents</a></li>
      <li><a href="pages/admin/analytics.php" class="nav-link">Analytics</a></li>
      <li><a href="pages/admin/requests.php" class="nav-link">Support</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main class="main-content">
  <section class="hero-banner">
    <div class="banner-overlay">
      <div class="banner-content">
        <h1>Welcome back, <?= htmlspecialchars($name) ?>!</h1>
        <p>Discover fresh, local produce and connect with farmers in your area. Shop smarter, support sustainably.</p>
        <?php if ($role === 'consumer'): ?>
          <a href="pages/consumer/browse.php" class="btn btn-primary btn-large">Start Shopping</a>
        <?php elseif ($role === 'farmer'): ?>
          <a href="pages/farmer/post_produce.php" class="btn btn-primary btn-large">List Your Produce</a>
        <?php endif; ?>
      </div>
    </div>
    <!-- Placeholder for hero image -->
    <div class="banner-image" style="background-image: url('https://via.placeholder.com/1920x500?text=Fresh+Produce+Market');"></div>
  </section>

  <section class="categories-section">
    <div class="container">
      <h2 class="section-title">Quick Actions</h2>
      <p class="section-subtitle">Explore essential features tailored to your role. Dive in and get started.</p>
      <div class="grid cards-grid">
        <?php if ($role === 'farmer'): ?>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-seedling fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/post_produce.php" class="card-link">Post Produce</a>
              </h3>
              <p class="card-description">Add your harvested produce for sale in the marketplace for buyers to see. Reach more customers effortlessly.</p>
              <div class="card-footer">
                <span class="card-badge">New Listing</span>
                <a href="pages/farmer/post_produce.php" class="btn btn-secondary btn-small">Post Now</a>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-truck fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/transport_request.php" class="card-link">Request Transport</a>
              </h3>
              <p class="card-description">Arrange pickup of your produce to collection centers or buyers quickly. Streamline your logistics.</p>
              <div class="card-footer">
                <button class="btn btn-secondary btn-small" onclick="simulateQuickRequest()">Quick Request</button>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-map-marker-alt fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/order_track.php" class="card-link">Order Tracking</a>
              </h3>
              <p class="card-description">Monitor the progress of your orders from placement to delivery. Stay updated in real-time.</p>
              <div class="card-footer">
                <span class="status-indicator active">Live Tracking</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-credit-card fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/payment_track.php" class="card-link">Payment Tracking</a>
              </h3>
              <p class="card-description">Check the status of payments for delivered produce. Ensure timely settlements with ease.</p>
              <div class="card-footer">
                <span class="card-badge success">Secure</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-line fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/submit_price.php" class="card-link">Submit Local Market Price</a>
              </h3>
              <p class="card-description">Share the prices you observe in local markets to help calculate averages. Contribute to fair pricing.</p>
              <div class="card-footer">
                <i class="fas fa-users" title="Community Driven"></i>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-bar fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/price_chart.php" class="card-link">View City Average Prices</a>
              </h3>
              <p class="card-description">See crowdsourced average market prices across major cities. Make informed selling decisions.</p>
              <div class="card-footer">
                <span class="card-badge">Trends</span>
              </div>
            </div>
          </div>
        <?php elseif ($role === 'consumer'): ?>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-shopping-basket fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/consumer/browse.php" class="card-link">Browse Produce</a>
              </h3>
              <p class="card-description">View and explore produce available from farmers near you. Fresh picks at your fingertips.</p>
              <div class="card-footer">
                <span class="card-badge">Fresh Daily</span>
                <a href="pages/consumer/browse.php" class="btn btn-secondary btn-small">Browse Now</a>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-tag fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/consumer/submit_price.php" class="card-link">Submit Market Price</a>
              </h3>
              <p class="card-description">Submit observed prices to contribute to city-wide average price data. Help build transparent markets.</p>
              <div class="card-footer">
                <i class="fas fa-heart" title="Support Local"></i>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-star fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/consumer/reviews.php" class="card-link">My Reviews</a>
              </h3>
              <p class="card-description">Leave feedback for farmers and view your past reviews. Share your experience and rate quality.</p>
              <div class="card-footer">
                <span class="status-indicator">Rate & Review</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-trending-up fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/price_chart.php" class="card-link">View Price Trends</a>
              </h3>
              <p class="card-description">Check trends and average prices for crops across different cities. Shop smarter with data.</p>
              <div class="card-footer">
                <span class="card-badge">Insights</span>
                <a href="pages/common/price_chart.php" class="btn btn-secondary btn-small">View Trends</a>
              </div>
            </div>
          </div>
        <?php elseif ($role === 'agent'): ?>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-check-circle fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/agent/approve_listings.php" class="card-link">Approve Listings</a>
              </h3>
              <p class="card-description">Verify and approve farmer produce listings for quality and accuracy. Maintain marketplace integrity.</p>
              <div class="card-footer">
                <span class="status-indicator pending">Pending Approvals</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-warehouse fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/agent/manage_center.php" class="card-link">Manage Collection Center</a>
              </h3>
              <p class="card-description">Monitor inventory and coordinate logistics at collection centers. Optimize operations seamlessly.</p>
              <div class="card-footer">
                <button class="btn btn-secondary btn-small" onclick="simulateInventoryCheck()">Check Inventory</button>
              </div>
            </div>
          </div>
        <?php elseif ($role === 'admin'): ?>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-user-shield fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/admin/approve_agents.php" class="card-link">Approve Agents</a>
              </h3>
              <p class="card-description">Review and approve new agent registrations with proper credentials. Ensure trusted partnerships.</p>
              <div class="card-footer">
                <span class="status-indicator pending">New Requests</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-pie fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/admin/analytics.php" class="card-link">Analytics</a>
              </h3>
              <p class="card-description">View system-wide analytics, trends, and reports for decision making. Drive platform growth.</p>
              <div class="card-footer">
                <span class="card-badge">Real-Time</span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-headset fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="support.php" class="card-link">Support</a>
              </h3>
              <p class="card-description">Handle user queries, disputes, and provide assistance efficiently. Keep the community thriving.</p>
              <div class="card-footer">
                <i class="fas fa-envelope" title="Messages: 5 New"></i>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Optional: Add a featured products section for consumers -->
  <?php if ($role === 'consumer'): ?>
  <section class="featured-products">
    <div class="container">
      <h2 class="section-title">Featured Fresh Produce</h2>
      <div class="grid cards-grid">
        <!-- Placeholder product cards; in real implementation, loop over data -->
        <?php 
        $featuredCrops = ['tomato', 'teff']; // Using available images
        foreach ($featuredCrops as $crop): 
        $imagePath = "assets/img/produce/{$crop}.png";
        $fullImagePath = __DIR__ . "/{$imagePath}";
        if (!file_exists($fullImagePath)) {
            $imagePath = "https://via.placeholder.com/300x200?text={$crop}";
        }
        $cropTitle = ucfirst($crop);
        $price = ($crop === 'tomato') ? '$2.99 / kg' : '$1.49 / kg';
        $desc = ($crop === 'tomato') ? 'Fresh from local farms. Ripe and ready.' : 'Crisp and juicy, hand-picked daily.';
        $rating = ($crop === 'tomato') ? '4.8' : '4.5';
        ?>
        <div class="card product-card">
          <div class="card-image">
            <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($cropTitle) ?>" style="width:100%; height:100%; object-fit:cover;">
          </div>
          <div class="card-content">
            <h3 class="card-title"><?= $cropTitle ?></h3>
            <p class="card-price"><?= $price ?></p>
            <p class="card-description"><?= $desc ?></p>
            <div class="card-footer">
              <button class="btn btn-primary btn-small">Add to Cart</button>
              <span class="card-rating"><i class="fas fa-star"></i> <?= $rating ?></span>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
        <!-- Add more as needed -->
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<script>
  // Simple interactive demo functions (replace with actual JS logic)
  function simulateQuickRequest() {
    alert('Transport request simulated! Redirecting...');
    // Actual: window.location.href = 'pages/farmer/transport_request.php';
  }
 
  function simulateInventoryCheck() {
    alert('Inventory check initiated! Loading data...');
    // Actual: window.location.href = 'pages/agent/manage_center.php';
  }
</script>
<script src="assets/js/app.js"></script>
</body>
</html>