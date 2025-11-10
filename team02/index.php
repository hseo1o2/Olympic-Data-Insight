<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
include 'partials/header.php';
?>

<h3 style="text-align:center; color:#1d3557; margin-top:20px;">📊 EPL 24–25 Dashboard</h3>

<div style="text-align:center; margin:20px auto; background:#fff; width:60%; padding:20px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.08);">
  <p>Welcome, <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b>!</p>
  <p>Your Role:
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
      <span style="color:#0077b6; font-weight:bold;"><?= strtoupper($_SESSION['user']['role']) ?></span>
    <?php else: ?>
      <span style="color:#2a9d8f; font-weight:bold;"><?= strtoupper($_SESSION['user']['role']) ?></span>
    <?php endif; ?>
  </p>
</div>

<div style="width:85%; margin:25px auto; background:white; padding:20px 25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
  <h3 style="color:#1d3557; margin-bottom:10px;">🏆 League Overview</h3>
  <div id="chart-container" style="height:400px; text-align:center; color:#aaa; padding-top:150px;">
    <em>Chart visualization area (to be implemented)</em>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
