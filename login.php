<?php
require_once __DIR__.'/includes/config.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Demo auth logic: use JSON agents/users if DB not configured
    $email = trim($_POST['email'] ?? '');
    $pass = trim($_POST['password'] ?? '');
    $role = $_POST['role'] ?? 'farmer';
    // Simple demo credentials mapping (email/password/role)
    $demos = [
      'farmer@example.com'=>['123','farmer',1,'Abebe ባለበሶው'],
      'consumer@example.com'=>['123','consumer',2,'Sara '],
      'agent@example.com'=>['123','agent',3,'ከቤ'],
      'admin@example.com'=>['123','admin',4,'Samuel']
    ];
    if (isset($demos[$email]) && $demos[$email][0] === $pass && $demos[$email][1] === $role) {
        $_SESSION['user_id'] = $demos[$email][2];
        $_SESSION['role'] = $role;
        $_SESSION['user_name'] = $demos[$email][3];
        header('Location: dashboard.php'); exit;
    } else {
        $error = "Invalid credentials (use farmer@.. consumer@.. agent@.. admin@.. with password 123 and matching role)";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Meret — Login</title>
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>
  <div class="login-container">
    <div class="form-side">
      <div class="welcome-section">
        <h1>Welcome to Meret</h1>
        <p>Simulate solutions for a thriving agricultural market.</p>
      </div>
      <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <form method="post" class="login-form">
        <div class="role-selection">
          <label class="role-btn active" for="role-farmer">
            <input type="radio" id="role-farmer" name="role" value="farmer" checked>
            <span>Farmer</span>
          </label>
          <label class="role-btn" for="role-consumer">
            <input type="radio" id="role-consumer" name="role" value="consumer">
            <span>Consumer</span>
          </label>
          <label class="role-btn" for="role-agent">
            <input type="radio" id="role-agent" name="role" value="agent">
            <span>Agent</span>
          </label>
        </div>
        <div class="input-group">
          <label class="input-label">
            <input type="email" name="email" placeholder="e.g. farmer@example.com" required>
            <span class="input-placeholder">Email</span>
          </label>
        </div>
        <div class="input-group">
          <label class="input-label">
            <input type="password" name="password" placeholder="Enter your password" required>
            <span class="input-placeholder">Password</span>
          </label>
        </div>
        <button class="login-btn" type="submit">Login</button>
      </form>
      <div class="social-login">
        <p>Or simulate social login</p>
        <div class="social-buttons">
          <button type="button" class="social-btn facebook">f</button>
          <button type="button" class="social-btn google">G</button>
        </div>
      </div>
      <!-- Admin Direct Login Button -->
      <div class="admin-login-section">
        <a href="admin.php" class="admin-btn">
          <i class="fas fa-user-shield"></i> I'm Admin
        </a>
      </div>
      <p class="back-link"><a href="index.php">&larr; Back</a></p>
    </div>
    <div class="graphic-side">
      <div class="wave-decoration"></div>
      <div class="ethiopian-logo">MERET</div>
      <h2>Harvest Better Tomorrow</h2>
      <p>farmers, consumers, and innovators.</p>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const radios = document.querySelectorAll('input[name="role"]');
      radios.forEach(radio => {
        radio.addEventListener('change', () => {
          document.querySelectorAll('.role-btn').forEach(btn => btn.classList.remove('active'));
          document.querySelector(`label[for="${radio.id}"]`).classList.add('active');
        });
      });
    });
  </script>
</body>
</html>