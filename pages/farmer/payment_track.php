<?php
// meret/pages/farmer/payment_track.php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'farmer') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$farmer_id = $_SESSION['user_id'] ?? null;
$paymentsFile = __DIR__ . '/../../data/payments.json';
$payments = file_exists($paymentsFile) ? json_decode(file_get_contents($paymentsFile), true) : [];
$myPayments = [];
if ($farmer_id) {
    foreach ($payments as $p) {
        if (($p['farmer_id'] ?? null) == $farmer_id) {
            $myPayments[] = $p;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Payment Tracking — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search payments...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="New Order">
      <i class="fas fa-plus"></i>
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
    <li><a href="payment_track.php" class="nav-link active">Payments</a></li>
    <li><a href="../../pages/common/submit_price.php" class="nav-link">Prices</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="payments-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Payment History</h1>
        <p class="section-subtitle">Track settlements for your delivered produce. All payments are secure and timely.</p>
      </div>
      <?php if (empty($myPayments)): ?>
        <div class="no-payments">
          <i class="fas fa-credit-card fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
          <p>No payments yet. Complete some orders to see records here.</p>
          <a href="order_track.php" class="btn btn-primary">View Orders</a>
        </div>
      <?php else: ?>
        <div class="payments-table-card">
          <div class="table-responsive">
            <table class="payments-table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Amount (ETB)</th>
                  <th>Status</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($myPayments as $pay): ?>
                  <tr>
                    <td><?= htmlspecialchars($pay['order_id'] ?? '') ?></td>
                    <td class="amount">ETB <?= number_format($pay['amount'] ?? 0, 2) ?></td>
                    <td>
                      <span class="status-indicator <?= strtolower($pay['status'] ?? 'pending') ?>">
                        <?= ucfirst(htmlspecialchars($pay['status'] ?? 'Pending')) ?>
                      </span>
                    </td>
                    <td><?= date('M d, Y', strtotime($pay['date'] ?? 'now')) ?></td>
                    <td>
                      <button class="btn btn-secondary btn-small">Details</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="payments-summary">
            <p><strong>Total Earned: ETB <?= number_format(array_sum(array_column($myPayments, 'amount')), 2) ?></strong></p>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>