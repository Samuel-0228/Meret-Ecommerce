<?php
// meret/pages/consumer/reviews.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Placeholder reviews data (mock from JSON or DB)
$reviews = [
    [
        'id' => 1,
        'order_id' => 'ORD001',
        'farmer' => 'Local Farmer',
        'crop' => 'Tomato',
        'stars' => 5,
        'comment' => 'Excellent quality, fresh and delivered on time!',
        'date' => '2025-12-01'
    ],
    [
        'id' => 2,
        'order_id' => 'ORD002',
        'farmer' => 'Bahir Dar Co-op',
        'crop' => 'Teff',
        'stars' => 4,
        'comment' => 'Good portion, but packaging could be better.',
        'date' => '2025-11-28'
    ]
];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mock save to reviews.json
    $newReview = [
        'id' => time(),
        'order_id' => $_POST['order_id'],
        'farmer' => $_POST['farmer'],
        'crop' => $_POST['crop'],
        'stars' => intval($_POST['stars']),
        'comment' => trim($_POST['comment']),
        'date' => date('Y-m-d')
    ];
    // In real: append to data/reviews.json
    $notice = 'Review submitted successfully! Thank you for your feedback.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>My Reviews — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search reviews...">
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
    <li><a href="browse.php" class="nav-link">Browse Produce</a></li>
    <li><a href="../../pages/common/price_chart.php" class="nav-link">Market Prices</a></li>
    <li><a href="reviews.php" class="nav-link active">Reviews</a></li>
    <li><a href="orders.php" class="nav-link">Orders</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="reviews-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">My Reviews</h1>
        <p class="section-subtitle">Share your experience with farmers and view past feedback.</p>
      </div>
      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>
      <div class="reviews-grid">
        <!-- Review Form -->
        <div class="card product-card review-form-card">
          <h3 class="card-title">Leave a Review</h3>
          <form method="POST" class="review-form">
            <div class="form-group">
              <label for="order_id">Order ID</label>
              <input type="text" id="order_id" name="order_id" placeholder="e.g., ORD001" required>
            </div>
            <div class="form-group">
              <label for="farmer">Farmer Name</label>
              <input type="text" id="farmer" name="farmer" placeholder="e.g., Local Farmer" required>
            </div>
            <div class="form-group">
              <label for="crop">Crop</label>
              <input type="text" id="crop" name="crop" placeholder="e.g., Tomato" required>
            </div>
            <div class="form-group">
              <label for="stars">Rating</label>
              <div class="star-rating">
                <input type="radio" id="star5" name="stars" value="5"><label for="star5"><i class="fas fa-star"></i></label>
                <input type="radio" id="star4" name="stars" value="4"><label for="star4"><i class="fas fa-star"></i></label>
                <input type="radio" id="star3" name="stars" value="3"><label for="star3"><i class="fas fa-star"></i></label>
                <input type="radio" id="star2" name="stars" value="2"><label for="star2"><i class="fas fa-star"></i></label>
                <input type="radio" id="star1" name="stars" value="1"><label for="star1"><i class="fas fa-star"></i></label>
              </div>
            </div>
            <div class="form-group">
              <label for="comment">Comment</label>
              <textarea id="comment" name="comment" rows="4" placeholder="Share your thoughts..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-small">Submit Review</button>
          </form>
        </div>
        <!-- Existing Reviews -->
        <div class="reviews-list">
          <?php foreach ($reviews as $review): ?>
            <div class="card product-card review-card">
              <div class="review-header">
                <h4 class="card-title"><?= htmlspecialchars($review['farmer']) ?> - <?= htmlspecialchars($review['crop']) ?></h4>
                <span class="order-badge">Order #<?= htmlspecialchars($review['order_id']) ?></span>
              </div>
              <div class="review-rating">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                  <i class="fas fa-star <?= $i <= $review['stars'] ? 'filled' : '' ?>"></i>
                <?php endfor; ?>
                <span class="rating-text"><?= $review['stars'] ?>/5</span>
              </div>
              <p class="card-description"><?= htmlspecialchars($review['comment']) ?></p>
              <div class="review-footer">
                <small><?= htmlspecialchars($review['date']) ?></small>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if (empty($reviews)): ?>
        <div class="no-reviews">
          <i class="fas fa-star fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
          <p>No reviews yet. Be the first to share your experience!</p>
          <a href="orders.php" class="btn btn-primary">View Orders</a>
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
      <a href="orders.php" class="bottom-nav-link" title="Orders">
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