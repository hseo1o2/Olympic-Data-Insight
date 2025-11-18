<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';
requireLogin();
include 'partials/header.php';

// 리그 / 시즌 정보 불러오기
$leagueStmt = $pdo->query("SELECT league_id, league_name FROM leagues ORDER BY league_id ASC");
$leagues = $leagueStmt->fetchAll(PDO::FETCH_ASSOC);

$seasonStmt = $pdo->query("SELECT season_id, season_name FROM seasons ORDER BY season_id DESC");
$seasons = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);

// 기본 표시값
$league = $leagues[0]['league_name'] ?? 'Premier League';
$season = $seasons[0]['season_name'] ?? '24-25';

// 팀 수와 경기 수 계산
$totalTeams = $pdo->query("SELECT COUNT(*) FROM teams")->fetchColumn();
$totalMatches = ($totalTeams * 38) / 2;
?>

<style>
body {
  font-family: 'Segoe UI', Arial, sans-serif;
  background-color: #f8fafc;
  color: #222;
}

h3 {
  color: #1d3557;
  text-align: center;
  margin-top: 25px;
  font-weight: 600;
}

.subtitle {
  color: #6c757d;
  text-align: center;
  margin-top: 40px;       
  margin-bottom: 50px;  
  line-height: 1.6;
}

.card {
  background: white;
  display: inline-block;
  width: 260px;
  margin: 10px;
  padding: 15px;
  border-radius: 10px;
  box-shadow: 0 2px 5px rgba(0,0,0,0.08);
  text-align: center;
}

select {
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 15px;
  margin: 5px;
}

.notice {
  color: #6c757d;
  font-size: 14px;
  text-align: center;
  margin-top: 10px;
}

.kpi-section {
  text-align: center;
  margin-top: 40px;
  margin-bottom: 60px;
}

.dashboard-welcome {
  text-align: center;
  margin: 20px 0;
  background-color: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
  max-width: 800px;  
  margin: 20px auto;  
}


</style>

<!-- ====================== 대시보드 환영 메시지 ====================== -->
<div class="dashboard-welcome">
  <p>Welcome, <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b>!</p>
  <p>Your Role:
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
      <span style="color:#0077b6; font-weight:bold;"><?= strtoupper($_SESSION['user']['role']) ?></span>
    <?php else: ?>
      <span style="color:#2a9d8f; font-weight:bold;"><?= strtoupper($_SESSION['user']['role']) ?></span>
    <?php endif; ?>
  </p>
</div>

<!-- ====================== 설명 ====================== -->
<h3>⚽ Football Performance Insight: EPL 24–25 Season Overview</h3>

<p class="subtitle">
  The Premier League (EPL) is England’s top-tier football league, where <strong>20 clubs</strong> compete in a <strong>38-match season</strong> — 19 home and 19 away — under a three-point system.<br>
  This service is based on data from the <strong><?= htmlspecialchars($season) ?></strong> season, providing analysis and visualization of  
  <strong>match results, team rankings, player rankings, and team scoring performance</strong>.<br>
  It is designed under the assumption that the season is currently in progress, allowing <strong>administrators to directly edit and update data as matches unfold</strong>.<br>
</p>

<!-- ====================== 리그 / 시즌 선택 ====================== -->
<div style="text-align:center; margin-bottom: 25px;">
  <form method="GET" action="">
    <label for="league">League:</label>
    <select name="league" id="league">
      <?php foreach ($leagues as $l): ?>
        <option value="<?= $l['league_id'] ?>" <?= ($l['league_name'] == $league) ? 'selected' : '' ?>>
          <?= htmlspecialchars($l['league_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label for="season">Season:</label>
    <select name="season" id="season">
      <?php foreach ($seasons as $s): ?>
        <option value="<?= $s['season_id'] ?>" <?= ($s['season_name'] == $season) ? 'selected' : '' ?>>
          <?= htmlspecialchars($s['season_name']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <button type="submit" style="padding:8px 15px;border:none;background:#1d3557;color:white;border-radius:6px;cursor:pointer;">
      Apply
    </button>
  </form>
</div>

<!-- ====================== 통계 ====================== -->
<div class="kpi-section">
  <div class="card">
    <h4>Total Matches</h4>
    <p style="font-size:22px;font-weight:bold;color:#1d3557;"><?= $totalMatches ?></p>
  </div>
  <div class="card">
    <h4>Teams Participating</h4>
    <p style="font-size:22px;font-weight:bold;color:#1d3557;"><?= $totalTeams ?></p>
  </div>
  <div class="card">
    <h4>Matches per Team</h4>
    <p style="font-size:22px;font-weight:bold;color:#1d3557;">38</p>
  </div>
</div>

<?php include 'partials/footer.php'; ?>
