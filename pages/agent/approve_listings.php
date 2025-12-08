<?php
// meret/pages/agent/approve_listings.php
require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'agent') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$produceFile = __DIR__ . '/../../data/produce.json';
$produce = file_exists($produceFile) ? json_decode(file_get_contents($produceFile), true) : [];
$notice = '';
// Handle approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_id'])) {
    foreach ($produce as &$p) {
        if ($p['id'] == $_POST['approve_id']) {
            $p['approved'] = true;
        }
    }
    file_put_contents($produceFile, json_encode($produce, JSON_PRETTY_PRINT));
    $notice = 'Produce approved successfully!';
}
unset($p); // avoid reference issues

// Separate pending and approved for display
$pendingListings = array_filter($produce, fn($p) => !($p['approved'] ?? true));
$approvedListings = array_filter($produce, fn($p) => $p['approved'] ?? false);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Approve Listings — Meret</title>
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
    <a href="#" class="cart-icon" title="Notifications">
      <i class="fas fa-bell"></i>
      <span class="cart-count">2</span>
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
    <li><a href="approve_listings.php" class="nav-link active">Approvals</a></li>
    <li><a href="manage_center.php" class="nav-link">Inventory</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="listings-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Approve Produce Listings</h1>
        <p class="section-subtitle">Verify farmer listings for quality and accuracy to maintain marketplace integrity.</p>
      </div>

      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>

      <!-- Summary Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-seedling"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($pendingListings) ?></h3>
            <p>Pending Listings</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($approvedListings) ?></h3>
            <p>Approved Listings</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($produce) ?></h3>
            <p>Total Listings</p>
          </div>
        </div>
      </div>

      <!-- Pending Listings -->
      <div class="listings-card">
        <h3>Pending Produce Listings <span class="badge"><?= count($pendingListings) ?></span></h3>
        <?php if (empty($pendingListings)): ?>
          <div class="no-listings">
            <i class="fas fa-seedling fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No pending listings. All farmer submissions are up to date.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="listings-table">
              <thead>
                <tr>
                  <th>Lot</th>
                  <th>Crop</th>
                  <th>Village</th>
                  <th>Quantity</th>
                  <th>Submitted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pendingListings as $listing): ?>
                  <tr>
                    <td><?= htmlspecialchars($listing['lot'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($listing['crop'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($listing['village'] ?? 'N/A') ?></td>
                    <td><?= number_format($listing['qty'] ?? 0, 2) ?> kg</td>
                    <td><?= date('M d, Y', strtotime($listing['created_at'] ?? 'now')) ?></td>
                    <td>
                      <form method="POST" style="display: inline;">
                        <input type="hidden" name="approve_id" value="<?= htmlspecialchars($listing['id']) ?>">
                        <button type="submit" class="btn btn-primary btn-small">
                          <i class="fas fa-check"></i> Approve
                        </button>
                      </form>
                      <button class="btn btn-secondary btn-small">
                        <i class="fas fa-eye"></i> View Details
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Approved Listings List -->
      <div class="listings-card" style="margin-top: 3rem;">
        <h3>Approved Listings <span class="badge"><?= count($approvedListings) ?></span></h3>
        <?php if (empty($approvedListings)): ?>
          <div class="no-listings">
            <i class="fas fa-check-circle fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No approved listings yet. Approve pending submissions above.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="listings-table">
              <thead>
                <tr>
                  <th>Lot</th>
                  <th>Crop</th>
                  <th>Village</th>
                  <th>Quantity</th>
                  <th>Approved On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($approvedListings as $listing): ?>
                  <tr>
                    <td><?= htmlspecialchars($listing['lot'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($listing['crop'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($listing['village'] ?? 'N/A') ?></td>
                    <td><?= number_format($listing['qty'] ?? 0, 2) ?> kg</td>
                    <td><?= date('M d, Y', strtotime($listing['approved_at'] ?? $listing['created_at'])) ?></td>
                    <td>
                      <button class="btn btn-secondary btn-small">
                        <i class="fas fa-eye"></i> View
                      </button>
                      <button class="btn btn-danger btn-small">
                        <i class="fas fa-times"></i> Revoke
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>