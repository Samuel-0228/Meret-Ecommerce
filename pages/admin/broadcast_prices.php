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
$pricesFile = __DIR__ . '/../../data/prices.json';
$prices = file_exists($pricesFile) ? json_decode(file_get_contents($pricesFile), true) : [];
$notice = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate SMS broadcast (no actual sending)
    $selectedCrops = $_POST['crops'] ?? [];
    $selectedCities = $_POST['cities'] ?? [];
    if (!empty($selectedCrops) || !empty($selectedCities)) {
        $notice = 'Daily prices broadcasted successfully to farmers via SMS!';
        // In real: integrate with SMS API like Twilio or Ethio Telecom
    } else {
        $notice = 'Please select at least one crop or city.';
    }
}

// Mock today's prices if empty
if (empty($prices)) {
    $prices = [
        ['crop' => 'Teff', 'city' => 'Addis Ababa', 'value' => 25.50, 'time' => date('Y-m-d H:i:s')],
        ['crop' => 'Tomato', 'city' => 'Bahir Dar', 'value' => 15.20, 'time' => date('Y-m-d H:i:s')],
        ['crop' => 'Onion', 'city' => 'Mekelle', 'value' => 10.80, 'time' => date('Y-m-d H:i:s')]
    ];
}

// Group by crop for display
$todayPrices = [];
foreach ($prices as $price) {
    $crop = $price['crop'] ?? 'Unknown';
    if (!isset($todayPrices[$crop])) $todayPrices[$crop] = [];
    $todayPrices[$crop][] = $price;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Daily Price Broadcast — Meret</title>
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
    <input type="text" class="search-input" placeholder="Search prices...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="requests.php" class="cart-icon" title="Notifications">
      <i class="fas fa-bell"></i>
      <span class="cart-count">1</span>
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
    <li><a href="requests.php" class="nav-link">Support</a></li>
    <li><a href="broadcast_prices.php" class="nav-link active">Broadcast Prices(via SMS)</a></li>
  </ul>
</nav>

<main class="main-content">
  <section class="broadcast-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Daily Price Broadcast</h1>
        <p class="section-subtitle">Send today's market prices to farmers via SMS for informed selling decisions.</p>
      </div>

      <?php if ($notice): ?>
        <div class="alert alert-success">
          <i class="fas fa-check-circle"></i> <?= htmlspecialchars($notice) ?>
        </div>
      <?php endif; ?>

      <!-- Today's Prices Overview -->
      <div class="prices-card">
        <h3>Today's Prices Overview</h3>
        <?php if (empty($todayPrices)): ?>
          <div class="no-prices">
            <i class="fas fa-chart-line fa-4x" style="color: var(--text-secondary); margin-bottom: 1rem;"></i>
            <p>No price data available today. Submit prices first to enable broadcast.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="prices-table">
              <thead>
                <tr>
                  <th>Crop</th>
                  <th>Cities & Prices (ETB/kg)</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($todayPrices as $crop => $cropPrices): ?>
                  <tr>
                    <td><?= htmlspecialchars($crop) ?></td>
                    <td>
                      <ul class="price-list">
                        <?php foreach ($cropPrices as $price): ?>
                          <li><?= htmlspecialchars($price['city']) ?>: ETB <?= number_format($price['value'], 2) ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </td>
                    <td>
                      <label class="checkbox-label">
                        <input type="checkbox" name="crops[]" value="<?= htmlspecialchars($crop) ?>" checked>
                        <span>Broadcast</span>
                      </label>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

      <!-- Broadcast Form -->
      <div class="broadcast-card">
        <h3>Broadcast Settings</h3>
        <form method="POST" class="broadcast-form">
          <div class="form-group">
            <label>Cities to Broadcast To</label>
            <div class="checkbox-grid">
              <label class="checkbox-label">
                <input type="checkbox" name="cities[]" value="Addis Ababa" checked> Addis Ababa
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="cities[]" value="Bahir Dar" checked> Bahir Dar
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="cities[]" value="Mekelle" checked> Mekelle
              </label>
              <label class="checkbox-label">
                <input type="checkbox" name="cities[]" value="Hawassa" checked> Hawassa
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>Message Template</label>
            <textarea name="message" rows="3" placeholder="Today's prices: Teff ETB 25.50/kg in Addis. Sell smart! Reply STOP to opt-out.">Today's market prices: {prices}. Harvest better with Meret! Reply STOP to opt-out.</textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">
            <i class="fas fa-paper-plane"></i> Send SMS Broadcast
          </button>
        </form>
        <div class="broadcast-info">
          <i class="fas fa-info-circle"></i> Estimated recipients: 12 farmers.
        </div>
      </div>
    </div>
  </section>
</main>

<script src="../../assets/js/app.js"></script>
</body>
</html>