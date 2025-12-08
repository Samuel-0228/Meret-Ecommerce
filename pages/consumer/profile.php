<?php
// pages/consumer/profile.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'consumer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$email = $_SESSION['user_email'] ?? 'user@example.com';
// Placeholder profile data
$profile = [
    'name' => $name,
    'email' => $email,
    'phone' => '+251-911-123456',
    'address' => 'Addis Ababa, Ethiopia',
    'joined' => '2025-01-15'
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission (placeholder)
    $_SESSION['user_name'] = $_POST['name'] ?? $name;
    $name = $_SESSION['user_name'];
    // In real app, update DB
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Profile — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search profile...">
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
    <li><a href="orders.php" class="nav-link">Orders</a></li>
    <li><a href="cart.php" class="nav-link">Cart</a></li>
    <li><a href="profile.php" class="nav-link active">Profile</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="profile-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">My Profile</h1>
        <p class="section-subtitle">Manage your account details.</p>
      </div>
      <div class="profile-card">
        <div class="card product-card">
          <div class="card-content">
            <form method="POST">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($profile['email']) ?>" required>
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($profile['phone']) ?>">
              </div>
              <div class="form-group">
                <label for="address">Address</label>
                <textarea id="address" name="address"><?= htmlspecialchars($profile['address']) ?></textarea>
              </div>
              <div class="profile-info">
                <p><strong>Joined:</strong> <?= htmlspecialchars($profile['joined']) ?></p>
              </div>
              <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
          </div>
        </div>
      </div>
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
      <a href="cart.php" class="bottom-nav-link" title="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="cart-count-bottom">0</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="profile.php" class="bottom-nav-link active" title="Profile">
        <i class="fas fa-user"></i>
        <span>Profile</span>
      </a>
    </li>
  </ul>
</nav>

<script src="../../assets/js/app.js"></script>
</body>
</html>