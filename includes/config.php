<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('BASE_PATH', __DIR__ . '/../');
define('LANG', $_GET['lang'] ?? $_SESSION['lang'] ?? 'en');
$_SESSION['lang'] = LANG;

$host = 'localhost'; $db = 'meret_db'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $pdo = null;
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('getUserRole')) {
    function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
}

if (!function_exists('getMockFile')) {
    function getMockFile($file) {
        return BASE_PATH . 'assets/data/' . $file . '.json';
    }
}
?>