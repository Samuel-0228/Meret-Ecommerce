<?php
// meret/pages/farmer/transport_request.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'farmer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$requestsFile = __DIR__ . '/../../data/transport_requests.json';
$requests = file_exists($requestsFile) ? json_decode(file_get_contents($requestsFile), true) : [];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newRequest = [
        'id' => time(),
        'farmer_id' => $_SESSION['user_id'],
        'produce_lot' => $_POST['lot'] ?? '',
        'notes' => $_POST['notes'] ?? '',
        'status' => 'pending',
        'created_at' => date('c')
    ];
    $requests[] = $newRequest;
    file_put_contents($requestsFile, json_encode($requests, JSON_PRETTY_PRINT));
    $notice = 'Transport request submitted successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Request Transport — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search requests...">
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
    <li><a href="post_produce.php" class="nav-link">Post Produce</a></li>
    <li><a href="order_track.php" class="nav-link">Orders</a></li>
    <li><a href="transport_request.php" class="nav-link active">Transport</a></li>
    <li><a href="../../pages/common/submit_price.php" class="nav-link">Prices</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="transport-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Request Transport</h1>
        <p class="section-subtitle">Arrange pickup for your produce to collection centers or buyers.</p>
      </div>
      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>
      <div class="form-card">
        <form method="POST" class="transport-form">
          <div class="form-group">
            <label for="lot">Lot Number</label>
            <input type="text" id="lot" name="lot" placeholder="e.g., LOT001" required>
          </div>
          <div class="form-group">
            <label for="notes">Pickup Notes</label>
            <textarea id="notes" name="notes" rows="4" placeholder="e.g., Ready for pickup tomorrow morning, located at village entrance"></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">Request Pickup</button>
        </form>
      </div>
      <?php if (!empty($requests)): ?>
        <div class="requests-list">
          <h3>Recent Requests</h3>
          <div class="grid cards-grid">
            <?php foreach ($requests as $request): ?>
              <?php if (($request['farmer_id'] ?? null) == $_SESSION['user_id']): ?>
                <div class="card product-card">
                  <div class="card-content">
                    <h3 class="card-title">Request #<?= htmlspecialchars($request['id']) ?></h3>
                    <p class="card-description">
                      Lot: <?= htmlspecialchars($request['produce_lot']) ?><br>
                      Status: <?= htmlspecialchars($request['status']) ?><br>
                      Created: <?= date('M d, Y', strtotime($request['created_at'])) ?>
                    </p>
                    <div class="card-footer">
                      <span class="status-indicator <?= strtolower($request['status']) ?>">
                        <?= ucfirst(htmlspecialchars($request['status'])) ?>
                      </span>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>