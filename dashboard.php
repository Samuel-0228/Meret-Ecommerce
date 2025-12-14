<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/auth.php';
checkLogin();
$role = getUserRole();
$name = $_SESSION['user_name'] ?? 'User';
// Load translations (assume translations.php is required in config.php)
$currentLang = $_SESSION['lang'] ?? 'en';
?>
<!DOCTYPE html>
<html lang="<?= $currentLang ?>">
<head>
  <meta charset="utf-8">
  <title><?= t('dashboard_title', $currentLang) ?> — Meret</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="dashboard.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="<?= t('search_placeholder', $currentLang) ?>">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="pages/consumer/cart.php" class="cart-icon" title="<?= t('cart', $currentLang) ?>">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count">0</span>
    </a>
    <div class="user-profile">
      <span class="user-name"><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</span>
      <a href="logout.php" class="logout-btn"><?= t('logout', $currentLang) ?></a>
      <!-- Language Selector -->
      <div class="lang-selector">
        <select onchange="changeLang(this.value)">
          <option value="en" <?= $currentLang === 'en' ? 'selected' : '' ?>>EN</option>
          <option value="am" <?= $currentLang === 'am' ? 'selected' : '' ?>>አማ</option>
          <option value="om" <?= $currentLang === 'om' ? 'selected' : '' ?>>OM</option>
        </select>
      </div>
    </div>
  </div>
</header>

<nav class="navbar">
  <ul class="nav-list">
    <li><a href="dashboard.php" class="nav-link active"><?= t('dashboard') ?></a></li>

    <?php if ($role === 'consumer'): ?>
      <li><a href="pages/consumer/browse.php" class="nav-link"><?= t('browse_produces') ?></a></li>
      <li><a href="pages/common/price_chart.php" class="nav-link"><?= t('market_prices') ?></a></li>
      <li><a href="pages/consumer/reviews.php" class="nav-link"><?= t('reviews') ?></a></li>
      <li><a href="pages/consumer/my_orders.php" class="nav-link"><?= t('my_orders') ?></a></li>

    <?php elseif ($role === 'farmer'): ?>
      <li><a href="pages/farmer/post_produce.php" class="nav-link"><?= t('post_produce') ?></a></li>
      <li><a href="pages/farmer/order_track.php" class="nav-link"><?= t('orders') ?></a></li>
      <li><a href="pages/common/submit_price.php" class="nav-link"><?= t('prices') ?></a></li>

    <?php elseif ($role === 'agent'): ?>
      <li><a href="pages/agent/approve_listings.php" class="nav-link"><?= t('approvals') ?></a></li>
      <li><a href="pages/agent/manage_center.php" class="nav-link"><?= t('inventory') ?></a></li>

    <?php elseif ($role === 'admin'): ?>
      <li><a href="pages/admin/approve_agents.php" class="nav-link"><?= t('agents') ?></a></li>
      <li><a href="pages/admin/analytics.php" class="nav-link"><?= t('analytics') ?></a></li>
      <li><a href="pages/admin/requests.php" class="nav-link"><?= t('support') ?></a></li>
      <li><a href="pages/admin/broadcast_prices.php" class="nav-link"><?= t('sms') ?></a></li>

    <?php endif; ?>
  </ul>
</nav>


<main class="main-content">
  <section class="hero-banner">
    <div class="banner-overlay">
      <div class="banner-content">
        <h1><?= t('welcome_back', $currentLang) ?>, <?= htmlspecialchars($name) ?>!</h1>
        <p><?= t('discover_fresh_produce', $currentLang) ?></p>
        <?php if ($role === 'consumer'): ?>
          <a href="pages/consumer/browse.php" class="btn btn-primary btn-large"><?= t('start_shopping', $currentLang) ?></a>
        <?php elseif ($role === 'farmer'): ?>
          <a href="pages/farmer/post_produce.php" class="btn btn-primary btn-large"><?= t('list_produce', $currentLang) ?></a>
        <?php endif; ?>
      </div>
    </div>
    <!-- Placeholder for hero image -->
    <div class="banner-image" style="background-image: url('https://via.placeholder.com/1920x500?text=Fresh+Produce+Market');"></div>
  </section>

  <section class="categories-section">
    <div class="container">
      <h2 class="section-title"><?= t('quick_actions', $currentLang) ?></h2>
      <p class="section-subtitle"><?= t('explore_features', $currentLang) ?></p>
      <div class="grid cards-grid">
        <?php if ($role === 'farmer'): ?>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-seedling fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/post_produce.php" class="card-link"><?= t('post_produce', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('add_harvest_produce', $currentLang) ?></p>
              <div class="card-footer">
                <span class="card-badge"><?= t('new_listing', $currentLang) ?></span>
                <a href="pages/farmer/post_produce.php" class="btn btn-secondary btn-small"><?= t('post_now', $currentLang) ?></a>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-truck fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/transport_request.php" class="card-link"><?= t('request_transport', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('arrange_pickup', $currentLang) ?></p>
              <div class="card-footer">
                <button class="btn btn-secondary btn-small" onclick="simulateQuickRequest()"><?= t('quick_request', $currentLang) ?></button>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-map-marker-alt fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/order_track.php" class="card-link"><?= t('order_tracking', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('monitor_orders', $currentLang) ?></p>
              <div class="card-footer">
                <span class="status-indicator active"><?= t('live_tracking', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-credit-card fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/farmer/payment_track.php" class="card-link"><?= t('payment_tracking', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('check_payments', $currentLang) ?></p>
              <div class="card-footer">
                <span class="card-badge success"><?= t('secure', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-line fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/submit_price.php" class="card-link"><?= t('submit_price', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('share_prices', $currentLang) ?></p>
              <div class="card-footer">
                <i class="fas fa-users" title="<?= t('community_driven', $currentLang) ?>"></i>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-bar fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/price_chart.php" class="card-link"><?= t('view_prices', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('see_averages', $currentLang) ?></p>
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
                <a href="pages/consumer/browse.php" class="card-link"><?= t('browse_produce', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('view_explore_produce', $currentLang) ?></p>
              <div class="card-footer">
                <span class="card-badge"><?= t('fresh_daily', $currentLang) ?></span>
                <a href="pages/consumer/browse.php" class="btn btn-secondary btn-small"><?= t('browse_now', $currentLang) ?></a>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-tag fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/consumer/submit_price.php" class="card-link"><?= t('submit_market_price', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('submit_observed_prices', $currentLang) ?></p>
              <div class="card-footer">
                <i class="fas fa-heart" title="<?= t('support_local', $currentLang) ?>"></i>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-star fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/consumer/reviews.php" class="card-link"><?= t('my_reviews', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('leave_feedback', $currentLang) ?></p>
              <div class="card-footer">
                <span class="status-indicator"><?= t('rate_review', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-trending-up fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/common/price_chart.php" class="card-link"><?= t('view_price_trends', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('check_trends_averages', $currentLang) ?></p>
              <div class="card-footer">
                <span class="card-badge">Insights</span>
                <a href="pages/common/price_chart.php" class="btn btn-secondary btn-small"><?= t('view_trends', $currentLang) ?></a>
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
                <a href="pages/agent/approve_listings.php" class="card-link"><?= t('approve_listings', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('verify_approve_listings', $currentLang) ?></p>
              <div class="card-footer">
                <span class="status-indicator pending"><?= t('pending_approvals', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-warehouse fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/agent/manage_center.php" class="card-link"><?= t('manage_collection_center', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('monitor_inventory_logistics', $currentLang) ?></p>
              <div class="card-footer">
                <button class="btn btn-secondary btn-small" onclick="simulateInventoryCheck()"><?= t('check_inventory', $currentLang) ?></button>
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
                <a href="pages/admin/approve_agents.php" class="card-link"><?= t('approve_agents', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('review_approve_agents', $currentLang) ?></p>
              <div class="card-footer">
                <span class="status-indicator pending"><?= t('new_requests', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-chart-pie fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/admin/analytics.php" class="card-link"><?= t('analytics', $currentLang)?></a>
              </h3>
              <p class="card-description"><?= t('view_system_analytics', $currentLang) ?></p>
              <div class="card-footer">
                <span class="card-badge"><?= t('real_time', $currentLang) ?></span>
              </div>
            </div>
          </div>
          <div class="card product-card">
            <div class="card-image">
              <i class="fas fa-headset fa-3x"></i>
            </div>
            <div class="card-content">
              <h3 class="card-title">
                <a href="pages/admin/requests.php" class="card-link"><?= t('support', $currentLang) ?></a>
              </h3>
              <p class="card-description"><?= t('handle_user_queries', $currentLang) ?></p>
              <div class="card-footer">
                <i class="fas fa-envelope" title="<?= t('messages_new', $currentLang) ?>"></i>
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
      <h2 class="section-title"><?= t('featured_fresh_produce', $currentLang) ?></h2>
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
        $price = ($crop === 'tomato') ? '49 birr  / kg' : '59 birr / kg';
        $desc = ($crop === 'tomato') ? t('fresh_tomatoes', $currentLang) : t('crisp_teff', $currentLang);
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
              <button class="btn btn-primary btn-small"><?= t('add_to_cart', $currentLang) ?></button>
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
    alert('<?= t('transport_simulated', $currentLang) ?>');
  }
 
  function simulateInventoryCheck() {
    alert('<?= t('inventory_initiated', $currentLang) ?>');
  }

  function changeLang(lang) {
    fetch('set_lang.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: 'lang=' + lang
    }).then(() => location.reload());
  }
</script>
<script src="assets/js/app.js"></script>
</body>
</html>