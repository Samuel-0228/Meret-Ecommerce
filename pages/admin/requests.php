<?php
// meret/pages/admin/requests.php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'admin') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Mock support requests/tickets data
$requests = [
    [
        'id' => 1,
        'user' => 'Abebe (Farmer)',
        'subject' => 'Order Delay Issue',
        'message' => 'My tomato order is delayed by 2 days. Need urgent resolution.',
        'status' => 'Open',
        'date' => '2025-12-06',
        'priority' => 'High'
    ],
    [
        'id' => 2,
        'user' => 'Sara (Consumer)',
        'subject' => 'Payment Not Credited',
        'message' => 'Payment for teff order was deducted but not reflected.',
        'status' => 'In Progress',
        'date' => '2025-12-05',
        'priority' => 'Medium'
    ],
    [
        'id' => 3,
        'user' => 'Kibe (Agent)',
        'subject' => 'Inventory Sync Error',
        'message' => 'Collection center inventory not updating in real-time.',
        'status' => 'Resolved',
        'date' => '2025-12-04',
        'priority' => 'Low'
    ],
    [
        'id' => 4,
        'user' => 'መልክኛው(Consumer)',
        'subject' => 'Listing Approval Delay',
        'message' => 'My review for onion farmer is pending approval.',
        'status' => 'Open',
        'date' => '2025-12-07',
        'priority' => 'High'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Support Requests — Meret</title>
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
    <li><a href="approve_agents.php" class="nav-link">Agents</a></li>
    <li><a href="analytics.php" class="nav-link">Analytics</a></li>
    <li><a href="requests.php" class="nav-link active">Support</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="requests-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Support Requests</h1>
        <p class="section-subtitle">Manage user inquiries, resolve issues, and track resolution times.</p>
      </div>

      <!-- Filters -->
      <div class="filters-bar">
        <div class="filter-group">
          <select class="filter-select">
            <option>All Status</option>
            <option>Open</option>
            <option>In Progress</option>
            <option>Resolved</option>
          </select>
        </div>
        <div class="filter-group">
          <select class="filter-select">
            <option>All Priority</option>
            <option>High</option>
            <option>Medium</option>
            <option>Low</option>
          </select>
        </div>
        <button class="btn btn-secondary">Export CSV</button>
      </div>

      <!-- Requests Table -->
      <div class="requests-table-card">
        <?php if (empty($requests)): ?>
          <div class="no-requests">
            <i class="fas fa-headset fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No support requests yet. Users will appear here when they reach out.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="requests-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>User</th>
                  <th>Subject</th>
                  <th>Message</th>
                  <th>Status</th>
                  <th>Priority</th>
                  <th>Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($requests as $request): ?>
                  <tr class="request-row <?= strtolower($request['status']) ?>">
                    <td>#<?= $request['id'] ?></td>
                    <td>
                      <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($request['user']) ?>
                      </div>
                    </td>
                    <td><?= htmlspecialchars($request['subject']) ?></td>
                    <td><?= htmlspecialchars(substr($request['message'], 0, 50)) ?>...</td>
                    <td>
                      <span class="status-indicator <?= strtolower($request['status']) ?>">
                        <?= ucfirst($request['status']) ?>
                      </span>
                    </td>
                    <td>
                      <span class="priority-badge <?= strtolower($request['priority']) ?>">
                        <?= ucfirst($request['priority']) ?>
                      </span>
                    </td>
                    <td><?= date('M d, Y', strtotime($request['date'])) ?></td>
                    <td>
                      <button class="btn btn-primary btn-small">View</button>
                      <button class="btn btn-secondary btn-small">Resolve</button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="requests-summary">
            <p>Total Requests: <?= count($requests) ?> | Open: <?= count(array_filter($requests, fn($r) => $r['status'] === 'Open')) ?> | Resolved: <?= count(array_filter($requests, fn($r) => $r['status'] === 'Resolved')) ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>