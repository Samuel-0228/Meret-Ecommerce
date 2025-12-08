<?php
// meret/pages/common/submit_price.php (consumer or common)
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
$name = $_SESSION['user_name'] ?? 'User';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $city = $_POST["city"] ?? '';
    $crop = $_POST["crop"] ?? '';
    $price = floatval($_POST["price"] ?? 0);

    if (!empty($city) && !empty($crop) && $price > 0) {
        $pricesFile = __DIR__ . '/../../data/prices.json';
        $prices = file_exists($pricesFile) ? json_decode(file_get_contents($pricesFile), true) : [];

        $prices[] = [
            "city" => $city,
            "crop" => $crop,
            "value" => $price,
            "user_id" => $_SESSION["user_id"] ?? 'anonymous',
            "time" => date("Y-m-d H:i:s")
        ];

        file_put_contents($pricesFile, json_encode($prices, JSON_PRETTY_PRINT));
        $msg = "Price submitted successfully! Thank you for contributing to transparent markets.";
    } else {
        $msg = "Please fill all fields correctly.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Submit Market Price — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search prices...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="Shopping Cart">
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
    <?php if ($role === 'consumer'): ?>
      <li><a href="../../pages/consumer/browse.php" class="nav-link">Browse Produce</a></li>
      <li><a href="../../pages/common/price_chart.php" class="nav-link">Market Prices</a></li>
      <li><a href="../../pages/consumer/reviews.php" class="nav-link">Reviews</a></li>
      <li><a href="../../pages/consumer/orders.php" class="nav-link">Orders</a></li>
    <?php elseif ($role === 'farmer'): ?>
      <li><a href="../../pages/farmer/post_produce.php" class="nav-link">Post Produce</a></li>
      <li><a href="../../pages/farmer/order_track.php" class="nav-link">Orders</a></li>
      <li><a href="../../pages/common/price_chart.php" class="nav-link">Prices</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main class="main-content">
  <section class="form-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Submit Market Price</h1>
        <p class="section-subtitle">Help build transparent markets by sharing observed prices from your local area.</p>
      </div>
      <?php if ($msg): ?>
        <div class="alert <?= strpos($msg, 'successfully') !== false ? 'alert-success' : 'alert-error' ?>">
          <i class="fas <?= strpos($msg, 'successfully') !== false ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
          <?= htmlspecialchars($msg) ?>
        </div>
      <?php endif; ?>
      <div class="form-card">
        <form method="POST" class="price-form">
          <div class="form-group">
            <label for="city">City</label>
            <select id="city" name="city" required>
              <option value="">Select City</option>
              <option value="Addis Ababa">Addis Ababa</option>
              <option value="Bahir Dar">Bahir Dar</option>
              <option value="Mekelle">Mekelle</option>
              <option value="Hawassa">Hawassa</option>
            </select>
          </div>
          <div class="form-group">
            <label for="crop">Crop</label>
            <input type="text" id="crop" name="crop" placeholder="e.g., Teff, Tomato, Onion" required>
          </div>
          <div class="form-group">
            <label for="price">Price (ETB per kg)</label>
            <input type="number" id="price" name="price" step="0.01" min="0" placeholder="e.g., 25.50" required>
          </div>
          <button type="submit" class="btn btn-primary btn-large">Submit Price</button>
        </form>
      </div>
      <div class="form-footer">
        <p><i class="fas fa-lightbulb"></i> Your contributions help farmers and buyers make smarter decisions.</p>
      </div>
    </div>
  </section>
</main>

<!-- Bottom Navigation for Mobile (if consumer role) -->
<?php if ($role === 'consumer'): ?>
<nav class="bottom-nav">
  <ul class="bottom-nav-list">
    <li class="bottom-nav-item">
      <a href="../../dashboard.php" class="bottom-nav-link" title="Home">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/orders.php" class="bottom-nav-link" title="Orders">
        <i class="fas fa-box"></i>
        <span>Orders</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/cart.php" class="bottom-nav-link" title="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="cart-count-bottom">0</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/profile.php" class="bottom-nav-link" title="Profile">
        <i class="fas fa-user"></i>
        <span>Profile</span>
      </a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<script src="../../assets/js/app.js"></script>
</body>
</html>