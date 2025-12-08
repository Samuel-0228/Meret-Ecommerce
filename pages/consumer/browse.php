<?php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$produceFile = __DIR__ . '/../../data/produce.json';
$produce = file_exists($produceFile) ? json_decode(file_get_contents($produceFile), true) : [];
// Default produce if JSON empty
if (empty($produce)) {
    $produce = [
        ["id"=>"p1","crop"=>"Tomato","qty"=>100,"lot"=>"LOT001","village"=>"Addis","farmer_id"=>"system","approved"=>true],
        ["id"=>"p2","crop"=>"Teff","qty"=>50,"lot"=>"LOT002","village"=>"Bahir Dar","farmer_id"=>"system","approved"=>true],
        ["id"=>"p3","crop"=>"Onion","qty"=>80,"lot"=>"LOT003","village"=>"Mekelle","farmer_id"=>"system","approved"=>true]
    ];
}
$name = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Browse Produce — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search produce...">
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
    <li><a href="../../pages/consumer/orders.php" class="nav-link">Orders</a></li>
    <li><a href="../../pages/consumer/cart.php" class="nav-link">Cart</a></li>
    <li><a href="../../pages/consumer/profile.php" class="nav-link">Profile</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="products-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Available Produce</h1>
        <p class="section-subtitle">Fresh picks from local farmers near you.</p>
      </div>
      <?php if ($produce): ?>
        <div class="grid cards-grid">
          <?php foreach ($produce as $item): ?>
            <?php if (!($item['approved'] ?? true)) continue; ?>
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
                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Fresh <?= htmlspecialchars($item['crop']) ?> from <?= htmlspecialchars($item['village']) ?>" style="width:100%; height:100%; object-fit:cover;">
              </div>
              <div class="card-content">
                <h3 class="card-title"><?= htmlspecialchars($item['crop']) ?></h3>
                <p class="card-price">Price: Contact Farmer</p> <!-- Placeholder; add price to data if available -->
                <p class="card-description">
                  Quantity Available: <?= htmlspecialchars($item['qty']) ?> kg<br>
                  Lot: <?= htmlspecialchars($item['lot']) ?><br>
                  Village: <?= htmlspecialchars($item['village']) ?>
                </p>
                <form method="POST" action="order.php" style="margin-top: auto;">
                  <input type="hidden" name="produce_id" value="<?= htmlspecialchars($item['id']) ?>">
                  <label class="quantity-label">Quantity (kg):
                    <input type="number" name="quantity" min="1" max="<?= htmlspecialchars($item['qty']) ?>" required style="width: 60px; padding: 0.5rem; border: 1px solid var(--border); border-radius: var(--radius);">
                  </label>
                  <button type="submit" class="btn btn-primary btn-small" style="width: 100%; margin-top: 0.5rem;">Order & Pay</button>
                </form>
                <div class="card-footer">
                  <span class="card-rating"><i class="fas fa-star"></i> 4.8 (12 reviews)</span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="no-products">
          <p>No produce available currently. Check back soon!</p>
          <a href="../../dashboard.php" class="btn btn-primary">Back to Dashboard</a>
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

<script src="../../assets/js/app.js"></script>
</body>
</html>