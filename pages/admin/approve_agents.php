<?php
// meret/pages/admin/approve_agents.php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'admin') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
$agentsFile = __DIR__ . '/../../data/agents.json';
$agents = file_exists($agentsFile) ? json_decode(file_get_contents($agentsFile), true) : [];
$notice = '';
// Handle approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_id'])) {
    foreach ($agents as &$a) {
        if ($a['id'] == $_POST['approve_id']) {
            $a['approved'] = true;
        }
    }
    file_put_contents($agentsFile, json_encode($agents, JSON_PRETTY_PRINT));
    $notice = 'Agent approved successfully!';
}
unset($a);

// Separate pending and approved for display
$pendingAgents = array_filter($agents, fn($a) => !$a['approved']);
$approvedAgents = array_filter($agents, fn($a) => $a['approved']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Agent Management — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search agents...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="Notifications">
      <i class="fas fa-bell"></i>
      <span class="cart-count">3</span>
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
    <li><a href="approve_agents.php" class="nav-link active">Agents</a></li>
    <li><a href="analytics.php" class="nav-link">Analytics</a></li>
    <li><a href="requests.php" class="nav-link">Support</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="agents-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Agent Management</h1>
        <p class="section-subtitle">Review and approve agent registrations to maintain marketplace integrity.</p>
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
            <i class="fas fa-user-plus"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($pendingAgents) ?></h3>
            <p>Pending Approvals</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($approvedAgents) ?></h3>
            <p>Approved Agents</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-clock"></i>
          </div>
          <div class="stat-content">
            <h3><?= count($agents) ?></h3>
            <p>Total Requests</p>
          </div>
        </div>
      </div>

      <!-- Pending Approvals -->
      <div class="agents-card">
        <h3>Pending Agent Requests <span class="badge"><?= count($pendingAgents) ?></span></h3>
        <?php if (empty($pendingAgents)): ?>
          <div class="no-agents">
            <i class="fas fa-user-check fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No pending agent requests. All registrations are up to date.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="agents-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>National ID</th>
                  <th>Date Submitted</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pendingAgents as $agent): ?>
                  <tr>
                    <td><?= htmlspecialchars($agent['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($agent['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($agent['national_id'] ?? 'N/A') ?></td>
                    <td><?= date('M d, Y', strtotime($agent['created_at'] ?? 'now')) ?></td>
                    <td>
                      <form method="POST" style="display: inline;">
                        <input type="hidden" name="approve_id" value="<?= htmlspecialchars($agent['id']) ?>">
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
          <?php endif; ?>
      </div>

      <!-- Approved Agents List -->
      <div class="agents-card" style="margin-top: 3rem;">
        <h3>Approved Agents <span class="badge"><?= count($approvedAgents) ?></span></h3>
        <?php if (empty($approvedAgents)): ?>
          <div class="no-agents">
            <i class="fas fa-users-slash fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No approved agents yet. Approve requests above to get started.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="agents-table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Approved On</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($approvedAgents as $agent): ?>
                  <tr>
                    <td><?= htmlspecialchars($agent['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($agent['email'] ?? '') ?></td>
                    <td><?= date('M d, Y', strtotime($agent['approved_at'] ?? $agent['created_at'])) ?></td>
                    <td>
                      <button class="btn btn-secondary btn-small">
                        <i class="fas fa-eye"></i> View Profile
                      </button>
                      <button class="btn btn-danger btn-small">
                        <i class="fas fa-ban"></i> Deactivate
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
      </div>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>