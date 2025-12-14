<?php
require_once __DIR__.'/includes/config.php';

// Default language
$lang = 'en';
if (isset($_GET['lang']) && in_array($_GET['lang'], ['en','am','or'])) {
    $lang = $_GET['lang'];
}

// Load translations
$translations = json_decode(file_get_contents(__DIR__."/lang/$lang.json"), true);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $translations['site_title'] ?></title>
  <link rel="stylesheet" href="assets/css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<header class="hero">
  <div class="hero-content">
    <h1><?= $translations['hero_title'] ?></h1>
    <p><?= $translations['hero_desc'] ?></p>
    <div class="hero-buttons">
      <a href="login.php" class="btn"><?= $translations['login'] ?></a>
      <a href="register.php" class="btn alt"><?= $translations['register'] ?></a>
      <a href="support.php" class="btn"><?= $translations['support'] ?></a>
    </div>
    <!-- Language Switcher -->
    <div class="language-switcher">
      <a href="?lang=en">English</a> |
      <a href="?lang=am">Amharic</a> |
      <a href="?lang=or">Oromo</a>
    </div>
  </div>
</header>

<section class="about">
  <div class="container">
    <h2><?= $translations['why_meret'] ?></h2>
    <p><?= $translations['about_desc'] ?></p>
    <div class="cards-grid">
      <?php foreach($translations['cards'] as $card): ?>
        <div class="card">
          <i class="fas fa-info-circle card-icon"></i>
          <h3><?= $card['title'] ?></h3>
          <p><?= $card['desc'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<footer>
  <?= $translations['footer'] ?>
</footer>

</body>
</html>
