<?php
// meret/support.php
require_once __DIR__.'/includes/config.php';


$role = getUserRole();
$name = $_SESSION['user_name'] ?? 'User';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Support — Meret</title>
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<header class="header">
  <div class="logo">
    <a href="dashboard.php"><i class="fas fa-leaf"></i> Meret</a>
  </div>
  <div class="search-container">
    <input type="text" class="search-input" placeholder="Search support...">
    <button class="search-btn"><i class="fas fa-search"></i></button>
  </div>
  <div class="header-actions">
    <a href="#" class="cart-icon" title="Shopping Cart">
      <i class="fas fa-shopping-cart"></i>
      <span class="cart-count">0</span>
    </a>
    <div class="user-profile">
      <span class="user-name"><?= htmlspecialchars($name) ?> (<?= htmlspecialchars($role) ?>)</span>
      <a href="logout.php" class="logout-btn">Logout</a>
    </div>
  </div>
</header>

<nav class="navbar">
  <ul class="nav-list">
    <li><a href="dashboard.php" class="nav-link">Home</a></li>
    <?php if ($role === 'admin'): ?>
      <li><a href="pages/admin/approve_agents.php" class="nav-link">Agents</a></li>
      <li><a href="pages/admin/analytics.php" class="nav-link">Analytics</a></li>
      <li><a href="support.php" class="nav-link active">Support</a></li>
    <?php else: ?>
      <li><a href="#" class="nav-link active">Support</a></li>
    <?php endif; ?>
  </ul>
</nav>

<main class="main-content">
  <section class="support-section">
    <div class="container">
      <div class="section-header">
        <h1 class="section-title">Support Center</h1>
        <p class="section-subtitle">Get help with your Meret account, orders, or marketplace questions. We're here to assist.</p>
      </div>
      <div class="support-grid">
        <!-- FAQ -->
        <div class="faq-card">
          <h3>Frequently Asked Questions</h3>
          <div class="faq-list">
            <details class="faq-item">
              <summary><i class="fas fa-question-circle"></i> How do I register as an agent?</summary>
              <p>Sign up as an agent during registration and submit your National ID. Our admin team will review and approve your account within 24-48 hours.</p>
            </details>
            <details class="faq-item">
              <summary><i class="fas fa-question-circle"></i> How do I submit a market price?</summary>
              <p>Navigate to "Market Prices" in your dashboard, select "Submit Price," choose your city, crop, and enter the observed price per kg. Your contribution helps everyone!</p>
            </details>
            <details class="faq-item">
              <summary><i class="fas fa-question-circle"></i> What if my order is delayed?</summary>
              <p>Contact the farmer directly via the order details or reach out to support. We'll mediate and ensure resolution.</p>
            </details>
            <details class="faq-item">
              <summary><i class="fas fa-question-circle"></i> How can farmers post produce?</summary>
              <p>Log in as a farmer, go to "Post Produce," add details like crop, quantity, and location. Listings are approved by agents for quality.</p>
            </details>
          </div>
        </div>
        <!-- Chat Support -->
        <div class="chat-card">
          <h3>Live Chat Support</h3>
          <div id="chat" class="chatbox">
            <div class="msg bot">Hello! How can we help you today? Type your message below.</div>
          </div>
          <div class="chat-input-container">
            <input id="chat-input" type="text" placeholder="Type your message..." />
            <button onclick="sendSupportMessage()" class="btn btn-primary btn-small"><i class="fas fa-paper-plane"></i> Send</button>
          </div>
        </div>
      </div>
      <div class="support-footer">
        <p>Still need help? <a href="mailto:support@meret.et">Email us at support@meret.et</a> or call +251-11-123-4567.</p>
      </div>
    </div>
  </section>
</main>

<script>
function sendSupportMessage() {
  const input = document.getElementById('chat-input');
  const chat = document.getElementById('chat');
  if (!input.value.trim()) return;

  // Add user message
  const userMsg = document.createElement('div');
  userMsg.className = 'msg me';
  userMsg.textContent = input.value.trim();
  chat.appendChild(userMsg);
  input.value = '';

  // Simulate bot response
  setTimeout(() => {
    const botMsg = document.createElement('div');
    botMsg.className = 'msg bot';
    botMsg.textContent = 'Thank you for your message! Our team will review and respond within 24 hours.';
    chat.appendChild(botMsg);
    chat.scrollTop = chat.scrollHeight;
  }, 1000);

  chat.scrollTop = chat.scrollHeight;
}

// Allow Enter key to send
document.getElementById('chat-input').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') sendSupportMessage();
});
</script>

<script src="assets/js/app.js"></script>
</body>
</html>