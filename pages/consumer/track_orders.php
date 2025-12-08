<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();

$userId = $_SESSION['user_id'];

// Load orders
$ordersFile = __DIR__ . '/../../data/orders.json';
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
$userOrders = array_filter($orders, fn($o) => $o['user_id'] === $userId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Orders — Meret</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
<header class="topbar">
    <div>Track Orders</div>
    <div>
        <span><?=htmlspecialchars($_SESSION['user_name'] ?? 'User')?></span>
        <a class="btn small" href="../../logout.php">Logout</a>
    </div>
</header>

<main class="container">
    <h2>My Orders</h2>

    <?php if ($userOrders): ?>
        <ul>
        <?php foreach ($userOrders as $order): ?>
            <li>
                <?=htmlspecialchars($order['produce_name'])?> — <?=htmlspecialchars($order['quantity'])?> kg —
                Status: <?=htmlspecialchars($order['status'])?> —
                Placed: <?=htmlspecialchars($order['time'])?>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <a href="browse.php" class="btn">Back to Browse</a>
</main>
</body>
</html>
