<?php
// meret/pages/farmer/post_produce.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'farmer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$produceFile = __DIR__ . '/../../data/produce.json';
$produce = file_exists($produceFile) ? json_decode(file_get_contents($produceFile), true) : [];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new = [
        'id' => uniqid(),
        'crop' => $_POST['crop'] ?? '',
        'qty' => floatval($_POST['qty'] ?? 0),
        'price' => floatval($_POST['price'] ?? 0),
        'lot' => $_POST['lot'] ?? 'LOT'.time(),
        'village' => $_POST['village'] ?? '',
        'harvest_date' => $_POST['harvest_date'] ?? date('Y-m-d'),
        'photo_url' => '',
        'farmer_id' => $_SESSION['user_id'] ?? 1,
        'approved' => true,
        'created_at' => date('c')
    ];
    $produce[] = $new;
    file_put_contents($produceFile, json_encode($produce, JSON_PRETTY_PRINT));
    $notice = "Produce posted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Post Produce — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search listings...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="Orders">
      <i class="fas fa-box"></i>
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
    <li><a href="post_produce.php" class="nav-link active">Post Produce</a></li>
    <li><a href="order_track.php" class="nav-link">Orders</a></li>
    <li><a href="../../pages/common/submit_price.php" class="nav-link">Prices</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="post-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Post New Produce</h1>
        <p class="section-subtitle">List your fresh harvest to reach buyers across Ethiopia.</p>
      </div>
      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>
      <div class="form-card">
        <form method="POST" class="post-form">
          <div class="form-group">
            <label for="crop">Crop</label>
            <input type="text" id="crop" name="crop" placeholder="e.g., Tomato, Teff" required>
          </div>
          <div class="form-group">
            <label for="qty">Quantity (kg)</label>
            <input type="number" id="qty" name="qty" step="0.01" min="0.01" placeholder="e.g., 50" required>
          </div>
          <div class="form-group">
            <label for="price">Price (Birr)</label>
            <input type="number" id="price" name="price" step="0.01" min="0.01" placeholder="e.g., 10" required>
          </div>
          <div class="form-group">
            <label for="lot">Lot Number</label>
            <input type="text" id="lot" name="lot" placeholder="e.g., LOT001">
          </div>
          <div class="form-group">
            <label for="village">Village / Location</label>
            <input type="text" id="village" name="village" placeholder="e.g., Bahir Dar" required>
          </div>
          <div class="form-group">
            <label for="harvest_date">Harvest Date</label>
            <input type="date" id="harvest_date" name="harvest_date" value="<?= date('Y-m-d') ?>">
          </div>
          <div class="form-group">
            <label for="photo">Upload Photo (Optional)</label>
            <input type="file" id="photo" name="photo" accept="image/*">
          </div>
          <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">Post Produce</button>
        </form>
      </div>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>