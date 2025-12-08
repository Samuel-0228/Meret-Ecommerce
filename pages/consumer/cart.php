<?php
// pages/consumer/cart.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Placeholder cart data
$cartItems = [
    [
        'id' => 'p1',
        'crop' => 'Tomato',
        'qty' => 10,
        'price' => 2.99,
        'total' => 29.90
    ],
    [
        'id' => 'p3',
        'crop' => 'Onion',
        'qty' => 5,
        'price' => 1.49,
        'total' => 7.45
    ]
];
$cartTotal = array_sum(array_column($cartItems, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Shopping Cart — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search cart...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="cart.php" class="cart-icon active" title="Shopping Cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count"><?= count($cartItems) ?></span>
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
    <li><a href="orders.php" class="nav-link">Orders</a></li>
    <li><a href="cart.php" class="nav-link active">Cart</a></li>
    <li><a href="profile.php" class="nav-link">Profile</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="cart-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Shopping Cart</h1>
        <p class="section-subtitle">Review your selected fresh produce.</p>
      </div>
      <?php if (!empty($cartItems)): ?>
        <div class="cart-items">
          <?php foreach ($cartItems as $item): ?>
            <?php 
            $cropLower = strtolower($item['crop']);
            $imagePath = "../../assets/img/produce/{$cropLower}.png";
            $fullImagePath = __DIR__ . "/{$imagePath}";
            if (!file_exists($fullImagePath)) {
                $imagePath = "../../assets/img/produce/default.png";
            }
            ?>
            <div class="card product-card">
              <div class="card-image">
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($item['crop']) ?>" style="width:100%; height:100%; object-fit:cover;">
              </div>
              <div class="card-content">
                <h3 class="card-title"><?= htmlspecialchars($item['crop']) ?></h3>
                <p class="card-price"><?= number_format($item['price'], 2) ?> / kg</p>
                <p class="card-description">Qty: <?= htmlspecialchars($item['qty']) ?> kg</p>
                <div class="card-footer">
                  <span class="card-price"><?= number_format($item['total'], 2) ?></span>
                  <button class="btn btn-secondary btn-small">Remove</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="cart-total">
          <div class="total-row">
            <span>Total: <?= number_format($cartTotal, 2) ?></span>
            <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
          </div>
        </div>
      <?php else: ?>
        <div class="no-products">
          <i class="fas fa-shopping-cart fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
          <p>Your cart is empty. Add some fresh produce!</p>
          <a href="browse.php" class="btn btn-primary">Continue Shopping</a>
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
      <a href="orders.php" class="bottom-nav-link" title="Orders">
        <i class="fas fa-box"></i>
        <span>Orders</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="cart.php" class="bottom-nav-link active" title="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="cart-count-bottom"><?= count($cartItems) ?></span>
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