<?php

function loadJSON($filename) {
    $path = __DIR__ . "/../data/" . $filename . ".json";

    if (!file_exists($path)) {
        return [];
    }

    $data = file_get_contents($path);
    return json_decode($data, true);
}

function saveJSON($filename, $data) {
    $dir = __DIR__ . "/../data/";

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $path = $dir . $filename . ".json";

    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
}
