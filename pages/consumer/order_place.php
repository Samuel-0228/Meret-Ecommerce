<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkLogin();

$userId = $_SESSION['user_id'];
$msg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $produceId = $_POST['produce_id'];
    $quantity = intval($_POST['quantity']);

    $produceFile = __DIR__ . '/../../data/produce.json';
    $produce = file_exists($produceFile) ? json_decode(file_get_contents($produceFile), true) : [];

    foreach ($produce as &$item) {
        if ($item['id'] == $produceId) {
            if ($item['quantity'] >= $quantity) {
                $item['quantity'] -= $quantity;

                // Save order
                $ordersFile = __DIR__ . '/../../data/orders.json';
                $orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];
                $orders[] = [
                    "id" => uniqid(),
                    "user_id" => $userId,
                    "produce_id" => $produceId,
                    "produce_name" => $item['name'],
                    "quantity" => $quantity,
                    "status" => "Pending Payment",
                    "time" => date("Y-m-d H:i:s")
                ];
                file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));

                $msg = "Order placed successfully! Proceed to payment.";
            } else {
                $msg = "Not enough stock available.";
            }
            break;
        }
    }
    file_put_contents($produceFile, json_encode($produce, JSON_PRETTY_PRINT));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Placed — Meret</title>
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
<header class="topbar">
    <div>Order Status</div>
    <div>
        <span><?=htmlspecialchars($_SESSION['user_name'] ?? 'User')?></span>
        <a class="btn small" href="../../logout.php">Logout</a>
    </div>
</header>

<main class="container">
    <h2>Order Status</h2>
    <p><?=htmlspecialchars($msg)?></p>
    <?php if ($msg === "Order placed successfully! Proceed to payment."): ?>
        <a href="payment.php" class="btn">Pay Now</a>
    <?php endif; ?>
    <a href="browse.php" class="btn">Back to Browse</a>
</main>
</body>
</html>
