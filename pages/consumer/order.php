<?php
// meret/pages/consumer/order.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$msg = '';
$orderId = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['produce_id']) && isset($_POST['quantity'])) {
        // Process order from browse.php
        $produceId = $_POST['produce_id'];
        $quantity = intval($_POST['quantity']);

        // Mock: Save to orders.json or DB
        $ordersFile = __DIR__ . '/../../data/orders.json';
        $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
        $orderId = 'ORD' . str_pad(count($orders) + 1, 3, '0', STR_PAD_LEFT);
        $orders[] = [
            'id' => $orderId,
            'produce_id' => $produceId,
            'quantity' => $quantity,
            'user_id' => $_SESSION['user_id'] ?? 'anonymous',
            'status' => 'Pending',
            'date' => date('Y-m-d H:i:s')
        ];
        file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
        $msg = "Order #{$orderId} placed successfully! Proceed to checkout.";
    } elseif (isset($_POST['action']) && $_POST['action'] === 'complete_order') {
        // Process checkout
        // Mock payment validation
        $cardNumber = $_POST['cardNumber'] ?? '';
        if (strlen($cardNumber) === 19) { // Basic check
            $msg = "Payment successful! Order confirmed. Check your orders.";
            // Update status in orders.json if needed
        } else {
            $msg = "Payment failed. Please check card details.";
        }
    }
    // Redirect or handle as needed; for prototype, show msg
    header('Location: checkout.php?msg=' . urlencode($msg));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Order Confirmation — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search...">
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
  <section class="order-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Order Placed</h1>
        <p class="section-subtitle">Your order has been confirmed. We'll notify you when it's ready for pickup.</p>
      </div>
      <?php if ($msg): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i>
          <?= htmlspecialchars($msg) ?>
        </div>
        <div class="order-details">
          <h3>Order #<?= htmlspecialchars($orderId ?? 'N/A') ?></h3>
          <p>Check your orders page for updates.</p>
          <a href="orders.php" class="btn btn-primary">View Orders</a>
        </div>
      <?php else: ?>
        <div class="no-order">
          <p>Redirecting to checkout...</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<!-- Bottom Navigation -->
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