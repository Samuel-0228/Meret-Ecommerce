<?php
// pages/consumer/orders.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Placeholder orders data
$orders = [
    [
        'id' => 'ORD001',
        'date' => '2025-12-01',
        'items' => 'Tomato (10kg), Onion (5kg)',
        'total' => '$25.00',
        'status' => 'Delivered'
    ],
    [
        'id' => 'ORD002',
        'date' => '2025-11-28',
        'items' => 'Teff (20kg)',
        'total' => '$40.00',
        'status' => 'Pending'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Orders — Meret</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="../../dashboard.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search orders...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="cart.php" class="cart-icon" title="Shopping Cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count">0</span>
    </a>
    <div class="user-profile">
      <span class="user-name"><?= htmlspecialchars($name) ?></span>
      <a href="../../logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<nav class="navbar">
  <ul class="nav-list">
    <li><a href="../../dashboard.php" class="nav-link">Home</a></li>
    <li><a href="orders.php" class="nav-link active">Orders</a></li>
    <li><a href="cart.php" class="nav-link">Cart</a></li>
    <li><a href="profile.php" class="nav-link">Profile</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="orders-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">My Orders</h1>
        <p class="section-subtitle">Track your recent purchases from local farmers.</p>
      </div>
      <?php if (!empty($orders)): ?>
        <div class="grid cards-grid">
          <?php foreach ($orders as $order): ?>
            <div class="card product-card">
              <div class="card-content" style="padding: 2rem;">
                <h3 class="card-title">Order #<?= htmlspecialchars($order['id']) ?></h3>
                <p class="card-description">
                  <strong>Date:</strong> <?= htmlspecialchars($order['date']) ?><br>
                  <strong>Items:</strong> <?= htmlspecialchars($order['items']) ?><br>
                  <strong>Total:</strong> <?= htmlspecialchars($order['total']) ?>
                </p>
                <div class="card-footer">
                  <span class="status-indicator <?= strtolower($order['status']) ?>"><?= htmlspecialchars($order['status']) ?></span>
                  <button class="btn btn-secondary btn-small">View Details</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-products">
          <i class="fas fa-box-open fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
          <p>No orders yet. Start shopping to see your purchases here!</p>
          <a href="browse.php" class="btn btn-primary">Browse Produce</a>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Bottom Navigation for Mobile E-commerce Style -->
<nav class="bottom-nav">
  <ul class="bottom-nav-list">
    <li class="bottom-nav-item">
      <a href="../../dashboard.php" class="bottom-nav-link" title="Home">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="orders.php" class="bottom-nav-link active" title="Orders">
        <i class="fas fa-box"></i>
        <span>Orders</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="cart.php" class="bottom-nav-link" title="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="cart-count-bottom">0</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="profile.php" class="bottom-nav-link" title="Profile">
        <i class="fas fa-user"></i>
        <span>Profile</span>
      </a>
    </li>
  </ul>
</nav>

<script src="../../assets/js/app.js"></script>
</body>
</html>