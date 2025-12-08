<?php

require_once __DIR__.'/../../includes/config.php';
require_once __DIR__.'/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'agent') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Placeholder inventory data
$inventory = [
    [
        'id' => 1,
        'crop' => 'Tomato',
        'qty' => 150,
        'unit' => 'kg',
        'low_stock' => false,
        'updated' => '2025-12-05'
    ],
    [
        'id' => 2,
        'crop' => 'Teff',
        'qty' => 80,
        'unit' => 'kg',
        'low_stock' => true,
        'updated' => '2025-12-04'
    ],
    [
        'id' => 3,
        'crop' => 'Onion',
        'qty' => 200,
        'unit' => 'kg',
        'low_stock' => false,
        'updated' => '2025-12-06'
    ]
];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mock add to inventory.json
    $newItem = [
        'id' => time(),
        'crop' => $_POST['crop'],
        'qty' => floatval($_POST['qty']),
        'unit' => 'kg',
        'low_stock' => floatval($_POST['qty']) < 50, // Alert threshold
        'updated' => date('Y-m-d')
    ];
    // In real: append to data/inventory.json
    $notice = 'Inventory updated successfully.';
    array_push($inventory, $newItem); // Mock append
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manage Inventory — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search inventory...">
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
    <li><a href="approve_listings.php" class="nav-link">Approvals</a></li>
    <li><a href="manage_center.php" class="nav-link active">Inventory</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="inventory-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Collection Center Inventory</h1>
        <p class="section-subtitle">Monitor stock levels, add new arrivals, and manage low-stock alerts for efficient operations.</p>
      </div>
      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>
      <!-- Add Inventory Form -->
      <div class="form-card">
        <h3>Add New Inventory</h3>
        <form method="POST" class="inventory-form">
          <div class="form-group">
            <label for="crop">Crop</label>
            <input type="text" id="crop" name="crop" placeholder="e.g., Tomato" required>
          </div>
          <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" id="qty" name="qty" step="0.01" min="0" placeholder="e.g., 100" required>
            <small>kg</small>
          </div>
          <button type="submit" class="btn btn-primary">Add to Inventory</button>
        </form>
      </div>
      <!-- Inventory Table -->
      <div class="inventory-table-card">
        <h3>Current Stock</h3>
        <?php if (empty($inventory)): ?>
          <div class="no-inventory">
            <i class="fas fa-warehouse fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No inventory items yet. Add some to get started.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="inventory-table">
              <thead>
                <tr>
                  <th>Crop</th>
                  <th>Quantity</th>
                  <th>Low Stock Alert</th>
                  <th>Last Updated</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($inventory as $item): ?>
                  <tr class="<?= $item['low_stock'] ? 'low-stock' : '' ?>">
                    <td><?= htmlspecialchars($item['crop']) ?></td>
                    <td><?= number_format($item['qty'], 2) ?> <?= htmlspecialchars($item['unit']) ?></td>
                    <td>
                      <span class="status-indicator <?= $item['low_stock'] ? 'warning' : 'success' ?>">
                        <?= $item['low_stock'] ? '<i class="fas fa-exclamation-triangle"></i> Low' : '<i class="fas fa-check"></i> OK' ?>
                      </span>
                    </td>
                    <td><?= htmlspecialchars($item['updated']) ?></td>
                    <td>
                      <button class="btn btn-secondary btn-small">Edit</button>
                      <button class="btn btn-danger btn-small">Remove</button>
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