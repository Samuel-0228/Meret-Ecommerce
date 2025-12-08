<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();

$phone = $_POST['phone'] ?? '';
$total = $_POST['total'] ?? 0;
$items = isset($_POST['items']) ? json_decode($_POST['items'], true) : [];

if (!$phone || !$total) {
    header("Location: checkout.php");
    exit;
}

// OPTIONAL: Save order into DB or JSON file
$orderId = "ORD-" . rand(100000, 999999);
$orderFile = __DIR__ . "/../../data/orders.json";

$existingOrders = file_exists($orderFile) ? json_decode(file_get_contents($orderFile), true) : [];

$existingOrders[] = [
    'order_id' => $orderId,
    'user_id' => $_SESSION['user_id'],
    'phone' => $phone,
    'items' => $items,
    'total' => $total,
    'date' => date("Y-m-d H:i:s"),
];

file_put_contents($orderFile, json_encode($existingOrders, JSON_PRETTY_PRINT));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Successful — Meret</title>
<link rel="stylesheet" href="../../assets/css/dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
  body { background: #f6f7f9; font-family: 'Inter', sans-serif; }
  .confirm-box {
      max-width: 450px;
      margin: 70px auto;
      background: #fff;
      padding: 30px;
      border-radius: 14px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .success-icon {
      font-size: 70px;
      color: #28a745;
      margin-bottom: 15px;
  }
  .btn {
      display: block;
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      border-radius: 8px;
      background: #198754;
      color: white;
      text-decoration: none;
  }
</style>
</head>
<body>

<div class="confirm-box">
    <i class="fas fa-check-circle success-icon"></i>
    <h2>Payment Successful</h2>
    <p>Your Telebirr payment has been processed.</p>

    <p><strong>Order ID:</strong> <?= $orderId ?></p>
    <p><strong>Total Paid:</strong> ETB <?= number_format($total, 2) ?></p>

    <a href="orders.php" class="btn">View Orders</a>
</div>

</body>
</html>
