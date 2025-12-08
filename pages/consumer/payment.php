<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();

$userId = $_SESSION['user_id'];
$msg = "";

// Load orders
$ordersFile = __DIR__ . '/../../data/orders.json';
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $orderId = $_POST['order_id'];

    foreach ($orders as &$order) {
        if ($order['id'] === $orderId && $order['user_id'] === $userId) {
            $order['status'] = "Paid";
            $msg = "Payment successful!";
            break;
        }
    }
    file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment — Meret</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
<header class="topbar">
    <div>Payment</div>
    <div>
        <span><?=htmlspecialchars($_SESSION['user_name'] ?? 'User')?></span>
        <a class="btn small" href="../../logout.php">Logout</a>
    </div>
</header>

<main class="container">
    <h2>Pending Payments</h2>

    <?php if ($msg) echo "<p style='color:green;'>$msg</p>"; ?>

    <?php 
    $pendingOrders = array_filter($orders, fn($o) => $o['user_id'] === $userId && $o['status'] === "Pending Payment");
    if ($pendingOrders):
    ?>
        <ul>
        <?php foreach ($pendingOrders as $order): ?>
            <li>
                <?=htmlspecialchars($order['produce_name'])?> — <?=htmlspecialchars($order['quantity'])?> kg
                <form method="POST" style="display:inline-block;">
                    <input type="hidden" name="order_id" value="<?=htmlspecialchars($order['id'])?>">
                    <button type="submit" class="btn small">Pay</button>
                </form>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No pending payments.</p>
    <?php endif; ?>

    <a href="track_orders.php" class="btn">Track Orders</a>
</main>
</body>
</html>
