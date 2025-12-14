<?php
// meret/admin.php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/auth.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    if ($email === 'admin@example.com' && $pass === '123') {
        $_SESSION['user_id'] = 4;
        $_SESSION['role'] = 'admin';
        $_SESSION['user_name'] = 'Admin';
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid admin credentials.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login — Meret</title>
  <link rel="stylesheet" href="assets/css/login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  <div class="login-container">
    <div class="form-side">
      <div class="welcome-section">
        <h1>Admin Portal</h1>
        <p>Access Meret admin dashboard.</p>
      </div>
      <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="POST" class="login-form">
        <div class="input-group">
          <label class="input-label">
            <input type="email" name="email" placeholder="admin@example.com" required>
            <span class="input-placeholder">Admin Email</span>
          </label>
        </div>
        <div class="input-group">
          <label class="input-label">
            <input type="password" name="password" placeholder="123" required>
            <span class="input-placeholder">Password</span>
          </label>
        </div>
        <button class="login-btn" type="submit">Admin Login</button>
      </form>
      <p class="back-link"><a href="login.php">&larr; Back to Login</a></p>
    </div>
    <div class="graphic-side">
      <div class="wave-decoration"></div>
      <div class="ethiopian-logo">MERET</div>
      <h2>Admin Access</h2>
      <p>Manage the marketplace.</p>
    </div>
  </div>
</body>
</html>