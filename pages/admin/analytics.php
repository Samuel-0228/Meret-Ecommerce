<?php

require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
if ($role !== 'admin') {
    header('Location: ../../dashboard.php');
    exit;
}
$name = $_SESSION['user_name'] ?? 'User';
// Mock data preparation
$produceFile = __DIR__ . '/../../data/produce.json';
$produce = file_exists($produceFile) ? json_decode(file_get_contents($produceFile), true) : [];
$inventoryFile = __DIR__ . '/../../data/inventory.json';
$inventory = file_exists($inventoryFile) ? json_decode(file_get_contents($inventoryFile), true) : [];

// Prepare data for charts
$cropCounts = [];
foreach ($produce as $p) {
    $crop = $p['crop'] ?? 'Unknown';
    $cropCounts[$crop] = ($cropCounts[$crop] ?? 0) + 1;
}

$inventoryCounts = [];
foreach ($inventory as $i) {
    $crop = $i['crop'] ?? 'Unknown';
    $inventoryCounts[$crop] = ($inventoryCounts[$crop] ?? 0) + floatval($i['qty'] ?? 0);
}

// Summary stats
$totalListings = count($produce);
$totalInventory = array_sum($inventoryCounts);
$activeCrops = count($cropCounts);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Analytics — Meret</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="../../dashboard.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search analytics...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="requests.php" class="cart-icon" title="Notifications">
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
    <li><a href="approve_agents.php" class="nav-link">Agents</a></li>
    <li><a href="analytics.php" class="nav-link active">Analytics</a></li>
    <li><a href="requests.php" class="nav-link">Support</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="analytics-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Analytics Dashboard</h1>
        <p class="section-subtitle">Real-time insights into marketplace activity, produce listings, and inventory trends.</p>
      </div>

      <!-- Summary Stats -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-seedling"></i>
          </div>
          <div class="stat-content">
            <h3><?= $totalListings ?></h3>
            <p>Total Listings</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-warehouse"></i>
          </div>
          <div class="stat-content">
            <h3><?= number_format($totalInventory, 2) ?> kg</h3>
            <p>Total Inventory</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <div class="stat-content">
            <h3><?= $activeCrops ?></h3>
            <p>Active Crops</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon">
            <i class="fas fa-users"></i>
          </div>
          <div class="stat-content">
            <h3>12</h3>
            <p>Total Users</p>
          </div>
        </div>
      </div>

      <!-- Charts Grid -->
      <div class="charts-grid">
        <div class="chart-card">
          <h3 class="chart-title">Produce Listings by Crop</h3>
          <div class="chart-wrapper">
            <canvas id="produceChart"></canvas>
          </div>
        </div>
        <div class="chart-card">
          <h3 class="chart-title">Inventory Distribution</h3>
          <div class="chart-wrapper">
            <canvas id="inventoryChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="activity-card">
        <h3>Recent Activity</h3>
        <ul class="activity-list">
          <li><i class="fas fa-plus"></i> New listing added: Tomato (50kg) by Farmer Abebe</li>
          <li><i class="fas fa-truck"></i> Transport request approved for Lot 001</li>
          <li><i class="fas fa-star"></i> Review submitted for Order ORD005 (5 stars)</li>
          <li><i class="fas fa-credit-card"></i> Payment processed: ETB 250 for Order ORD004</li>
        </ul>
      </div>
    </div>
  </section>
</main>

<script>
const produceData = <?= json_encode($cropCounts) ?>;
const inventoryData = <?= json_encode($inventoryCounts) ?>;

// Produce Chart
const ctx1 = document.getElementById('produceChart').getContext('2d');
new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: Object.keys(produceData),
        datasets: [{
            data: Object.values(produceData),
            backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444', '#8b5cf6'],
            borderWidth: 0,
            cutout: '60%'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 12 }, padding: 20 }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white'
            }
        },
        animation: {
            animateRotate: true,
            duration: 2000
        }
    }
});

// Inventory Chart
const ctx2 = document.getElementById('inventoryChart').getContext('2d');
new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: Object.keys(inventoryData),
        datasets: [{
            label: 'Quantity (kg)',
            data: Object.values(inventoryData),
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
            borderColor: 'rgb(16, 185, 129)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.1)' },
                ticks: { color: 'rgb(107, 114, 128)' }
            },
            x: {
                grid: { display: false },
                ticks: { color: 'rgb(107, 114, 128)' }
            }
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleColor: 'white',
                bodyColor: 'white'
            }
        },
        animation: {
            duration: 1500,
            easing: 'easeInOutQuart'
        }
    }
});
</script>

<script src="../../assets/js/app.js"></script>
</body>
</html>