<?php
// meret/pages/consumer/checkout.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';

// Placeholder cart data from session or DB; for prototype, use mock
$cartItems = [
    [
        'crop' => 'Tomato',
        'qty' => 10,
        'price' => 2.99,
        'total' => 29.90
    ]
];

$cartTotal = array_sum(array_column($cartItems, 'total'));
$tax = $cartTotal * 0.15;
$grandTotal = $cartTotal + $tax;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Checkout — Meret</title>
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
    <li><a href="cart.php" class="nav-link">Cart</a></li>
    <li><a href="profile.php" class="nav-link">Profile</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="checkout-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Checkout</h1>
        <p class="section-subtitle">Review your order and complete payment securely.</p>
      </div>

      <div class="checkout-grid">
        <div class="order-summary">
          <h3>Order Summary</h3>

          <?php foreach ($cartItems as $item): ?>
            <?php
            $cropLower = strtolower($item['crop']);
            $imagePath = "../../assets/img/produce/{$cropLower}.png";
            $fullImagePath = __DIR__ . "/{$imagePath}";
            if (!file_exists($fullImagePath)) {
                $imagePath = "../../assets/img/produce/default.png";
            }
            ?>
            <div class="summary-item">
              <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($item['crop']) ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
              <div>
                <p><?= htmlspecialchars($item['crop']) ?> (<?= $item['qty'] ?> kg)</p>
                <small>ETB <?= number_format($item['price'], 2) ?>/kg</small>
              </div>
              <span>ETB <?= number_format($item['total'], 2) ?></span>
            </div>
          <?php endforeach; ?>

          <div class="summary-total">
            <p>Subtotal: ETB <?= number_format($cartTotal, 2) ?></p>
            <p>Tax (15%): ETB <?= number_format($tax, 2) ?></p>
            <p class="grand-total">Grand Total: ETB <?= number_format($grandTotal, 2) ?></p>
          </div>
        </div>

        <div class="payment-form">
          <h3>Telebirr Payment</h3>

          <!-- ONLY CHANGE BELOW: sending to confirmation page -->
          <form method="POST" action="payment_confirmation.php">
            <input type="hidden" name="total" value="<?= $grandTotal ?>">
            <input type="hidden" name="items" value="<?= htmlspecialchars(json_encode($cartItems)) ?>">

            <div class="form-group">
              <label for="phone">Phone Number</label>
              <input type="tel" id="phone" name="phone" placeholder="+251 9XX XXX XXX" required>
              <small>Enter your Telebirr registered number (simulation).</small>
            </div>

            <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
              <i class="fas fa-mobile-alt"></i> Pay ETB <?= number_format($grandTotal, 2) ?> with Telebirr
            </button>
          </form>

          <div class="payment-info">
            <i class="fas fa-lock"></i> Santim Pay simulation: Clicking "Pay" will confirm the order.
          </div>
        </div>

      </div>
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
