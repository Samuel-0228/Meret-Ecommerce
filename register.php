<?php

require_once __DIR__.'/includes/config.php';
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = $_POST['role'] ?? 'farmer';
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = trim($_POST['password'] ?? ''); // Add password for real auth
    if (empty($name) || empty($email) || empty($password)) {
        $msg = 'Please fill all fields.';
    } else {
        // Mock registration
        if ($role === 'agent') {
            $agentsFile = __DIR__ . '/data/agents.json';
            $agents = file_exists($agentsFile) ? json_decode(file_get_contents($agentsFile), true) : [];
            $agents[] = [
                'id' => time(),
                'name' => $name,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), // Secure hash
                'national_id' => trim($_POST['national_id'] ?? ''),
                'approved' => false,
                'created_at' => date('c')
            ];
            file_put_contents($agentsFile, json_encode($agents, JSON_PRETTY_PRINT));
            $msg = 'Agent registration submitted. Await admin approval (24-48 hours).';
        } else {
            // For farmer/consumer: Mock success, in real: insert to users DB
            $_SESSION['user_id'] = time();
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $role;
            header('Location: dashboard.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register — Meret</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="index.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="header-actions">
    <div class="user-profile">
      <a href="login.php" class="btn btn-secondary">Login</a>
    </div>
  </div>
</header>

<main class="main-content">
  <section class="register-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Join Meret</h1>
        <p class="section-subtitle">Create your account to connect with farmers, agents, and markets.</p>
      </div>
      <div class="auth-card">
        <?php if ($msg): ?>
          <div class="alert <?= strpos($msg, 'submitted') !== false ? 'alert-success' : 'alert-error' ?>">
            <i class="fas <?= strpos($msg, 'submitted') !== false ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
            <?= htmlspecialchars($msg) ?>
          </div>
        <?php endif; ?>
        <form method="POST" class="auth-form">
          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" placeholder="e.g., kebede" required>
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="e.g., kebede@example.com" required>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Choose a secure password" required>
          </div>
          <div class="form-group">
            <label for="role">Account Type</label>
            <select id="role" name="role" required>
              <option value="">Select Type</option>
              <option value="farmer">Farmer</option>
              <option value="consumer">Consumer</option>
              <option value="agent">Agent (Requires Approval)</option>
            </select>
          </div>
          <div id="agent-fields" class="form-group" style="display: none;">
            <label for="national_id">National ID (for Agents)</label>
            <input type="text" id="national_id" name="national_id" placeholder="e.g., 1234567890">
          </div>
          <button type="submit" class="btn btn-primary btn-large" style="width: 100%;">Create Account</button>
        </form>
        <div class="auth-footer">
          <p>Already have an account? <a href="login.php">Sign in here</a>.</p>
        </div>
      </div>
    </div>
  </section>
</main>

<script>
document.getElementById('role').addEventListener('change', function() {
  const agentFields = document.getElementById('agent-fields');
  agentFields.style.display = this.value === 'agent' ? 'block' : 'none';
});
</script>

<script src="assets/js/app.js"></script>
</body>
</html>