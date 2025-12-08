<?php
// meret/pages/common/price_chart.php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();
$role = getUserRole();
$name = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Market Prices — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search prices...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="Shopping Cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count">0</span>
    </a>
    <div class="user-profile">
      <span class="user-name"><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</span>
      <a href="../../logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<nav class="navbar">
  <ul class="nav-list">
    <li><a href="../../dashboard.php" class="nav-link">Home</a></li>
    <?php if ($role === 'consumer'): ?>
      <li><a href="../../pages/consumer/browse.php" class="nav-link">Browse Produce</a></li>
      <li><a href="../../pages/common/price_chart.php" class="nav-link active">Market Prices</a></li>
      <li><a href="../../pages/consumer/reviews.php" class="nav-link">Reviews</a></li>
      <li><a href="../../pages/consumer/orders.php" class="nav-link">Orders</a></li>
    <?php elseif ($role === 'farmer'): ?>
      <li><a href="../../pages/farmer/post_produce.php" class="nav-link">Post Produce</a></li>
      <li><a href="../../pages/farmer/order_track.php" class="nav-link">Orders</a></li>
      <li><a href="../../pages/common/price_chart.php" class="nav-link active">Prices</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main class="main-content">
  <section class="chart-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Market Price Trends</h1>
        <p class="section-subtitle">Track average prices across Ethiopian cities. Filter by crop for insights.</p>
      </div>
      <div class="chart-controls">
        <input type="text" id="cropInput" class="search-input" placeholder="e.g., Teff, Tomato, Onion" style="width: 200px; margin-right: 1rem;">
        <button class="btn btn-primary" onclick="loadChart()">Filter Chart</button>
      </div>
      <div class="chart-wrapper">
        <canvas id="priceChart"></canvas>
      </div>
      <div class="chart-legend">
        <p><i class="fas fa-info-circle"></i> Data sourced from community submissions. Updated as of <?= date('F d, Y') ?>.</p>
      </div>
    </div>
  </section>
</main>

<!-- Bottom Navigation for Mobile (if consumer role) -->
<?php if ($role === 'consumer'): ?>
<nav class="bottom-nav">
  <ul class="bottom-nav-list">
    <li class="bottom-nav-item">
      <a href="../../dashboard.php" class="bottom-nav-link" title="Home">
        <i class="fas fa-home"></i>
        <span>Home</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/orders.php" class="bottom-nav-link" title="Orders">
        <i class="fas fa-box"></i>
        <span>Orders</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/cart.php" class="bottom-nav-link" title="Cart">
        <i class="fas fa-shopping-cart"></i>
        <span>Cart</span>
        <span class="cart-count-bottom">0</span>
      </a>
    </li>
    <li class="bottom-nav-item">
      <a href="../../pages/consumer/profile.php" class="bottom-nav-link" title="Profile">
        <i class="fas fa-user"></i>
        <span>Profile</span>
      </a>
    </li>
  </ul>
</nav>
<?php endif; ?>

<script>
let chartInstance = null;

function loadChart() {
    const crop = document.getElementById("cropInput").value.trim();
    const url = `get_avg_prices.php?crop=${encodeURIComponent(crop)}`;

    fetch(url)
        .then(res => res.json())
        .then(data => {
            const ctx = document.getElementById("priceChart").getContext("2d");
            const labels = Object.keys(data);
            const values = Object.values(data).map(d => d.average);

            if (chartInstance) {
                chartInstance.destroy();
            }

            chartInstance = new Chart(ctx, {
                type: 'line', // Modern line chart for trends
                data: {
                    labels: labels,
                    datasets: [{
                        label: `${crop || 'All Crops'} Average Price (ETB/kg)`,
                        data: values,
                        borderColor: 'rgb(16, 185, 129)', // Primary green
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4, // Smooth curves
                        fill: true,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: { font: { size: 14 }, color: 'rgb(17, 24, 39)' }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: 'white',
                            bodyColor: 'white',
                            borderColor: 'rgb(16, 185, 129)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    return `ETB ${context.parsed.y.toFixed(2)}/kg (${data[context.label].count} submissions)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { color: 'rgb(107, 114, 128)' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.1)' },
                            ticks: { 
                                color: 'rgb(107, 114, 128)',
                                callback: function(value) { return 'ETB ' + value; }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeInOutQuart'
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error loading chart:', error);
            alert('Failed to load price data. Please try again.');
        });
}

// Load initial chart on page load
document.addEventListener('DOMContentLoaded', loadChart);
</script>

<script src="../../assets/js/app.js"></script>
</body>
</html>