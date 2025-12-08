<?php
// meret/pages/farmer/order_track.php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'farmer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$farmerId = $_SESSION['user_id'] ?? null;
// Mock orders data
$ordersFile = __DIR__ . '/../../data/orders.json';
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
$farmerOrders = [];
if ($farmerId) {
    foreach ($orders as $order) {
        if (($order['farmer_id'] ?? null) == $farmerId) {
            $farmerOrders[] = $order;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Order Tracking — Meret</title>
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
    <a href="#" class="cart-icon" title="New Order">
      <i class="fas fa-plus"></i>
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
    <li><a href="post_produce.php" class="nav-link">Post Produce</a></li>
    <li><a href="order_track.php" class="nav-link active">Orders</a></li>
    <li><a href="../../pages/common/submit_price.php" class="nav-link">Prices</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="orders-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Order Tracking</h1>
        <p class="section-subtitle">Monitor the progress of your sales from placement to delivery.</p>
      </div>
      <?php if (empty($farmerOrders)): ?>
        <div class="no-orders">
          <i class="fas fa-box-open fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
          <p>No orders yet. Post some produce to start receiving sales!</p>
          <a href="post_produce.php" class="btn btn-primary">Post Produce</a>
        </div>
      <?php else: ?>
        <div class="grid cards-grid">
          <?php foreach ($farmerOrders as $order): ?>
            <div class="card product-card order-card">
              <div class="order-header">
                <h3 class="card-title">Order #<?= htmlspecialchars($order['id'] ?? '-') ?></h3>
                <span class="status-indicator <?= strtolower($order['status'] ?? 'pending') ?>">
                  <?= htmlspecialchars($order['status'] ?? 'Pending') ?>
                </span>
              </div>
              <p class="card-description">
                Crop: <?= htmlspecialchars($order['crop'] ?? 'N/A') ?><br>
                Quantity: <?= htmlspecialchars($order['quantity'] ?? 'N/A') ?> kg<br>
                Buyer: <?= htmlspecialchars($order['buyer_name'] ?? 'N/A') ?><br>
                Placed: <?= htmlspecialchars($order['date'] ?? '-') ?>
              </p>
              <div class="card-footer">
                <button class="btn btn-secondary btn-small">View Details</button>
                <button class="btn btn-primary btn-small">Update Status</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>