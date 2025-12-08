<?php
// meret/pages/common/get_avg_prices.php
require_once "../../includes/config.php";

$crop = $_GET["crop"] ?? null;

$pricesFile = __DIR__ . '/../../data/prices.json';
$prices = file_exists($pricesFile) ? json_decode(file_get_contents($pricesFile), true) : [];

// Prepare city groups
$cities = ["Addis Ababa", "Bahir Dar", "Mekelle", "Hawassa"];
$result = [];

foreach ($cities as $city) {
    $sum = 0;
    $count = 0;

    foreach ($prices as $p) {
        if ($p["city"] === $city && ($crop === null || $p["crop"] === $crop)) {
            $sum += $p["value"];
            $count++;
        }
    }

    $result[$city] = [
        "average" => ($count > 0) ? round($sum / $count, 2) : 0,
        "count" => $count
    ];
}

header('Content-Type: application/json');
echo json_encode($result);
?>