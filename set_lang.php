<?php
session_start();
if (isset($_POST['lang']) && in_array($_POST['lang'], ['en', 'am', 'om'])) {
    $_SESSION['lang'] = $_POST['lang'];
}
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
?>