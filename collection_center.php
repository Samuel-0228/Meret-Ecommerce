<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/mock_data.php';
$inv = file_exists(jsonPath('inventory')) ? json_decode(file_get_contents(jsonPath('inventory')),true) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><title>Collection Center</title><link rel="stylesheet" href="assets/css/style.css"></head>
<body>
  <main class="container">
    <h2>Collection Center Inventory</h2>
    <?php if (empty($inv)): ?>
      <p>No stock data yet.</p>
    <?php else: ?>
      <table class="table">
        <thead><tr><th>Crop</th><th>Qty</th><th>Fridge %</th><th>Low Stock</th></tr></thead>
        <tbody>
        <?php foreach($inv as $it): ?>
          <tr>
            <td><?=htmlspecialchars($it['crop'] ?? '')?></td>
            <td><?=htmlspecialchars($it['qty'] ?? '')?></td>
            <td><?=htmlspecialchars($it['fridge_usage'] ?? '')?>%</td>
            <td><?=!empty($it['low_stock_alert']) ? 'Yes' : 'No'?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <p><a href="dashboard.php">&larr; Back</a></p>
  </main>
</body>
</html>
