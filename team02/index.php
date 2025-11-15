<?php
$labels = ['2020', '2021', '2022'];
$values = [30, 25, 50];
?>

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

<!-- 메달(=득점) 비율 도넛 -->
<section class="card">
  <h3>Team Goals Ratio (League/Season)</h3>
  <div style="max-width:720px">
    <canvas id="chMedalRatio" height="220"></canvas>
  </div>
</section>

<!-- 연도별 성과 라인 -->
<section class="card">
  <h3>Yearly Performance – Avg Goals per Match</h3>
  <div style="max-width:720px">
    <canvas id="chYearlyLine" height="220"></canvas>
  </div>
</section>

<script>
(async () => {
  // 필요시 드롭다운에서 읽어옴 (없다면 null로 유지)
  const leagueId = $('#selLeague')?.value || null; // 선택 요소가 있다면 id 맞춰 쓰기
  const seasonId = $('#selSeason')?.value || null;

  // 1) 득점 비율(도넛)
  {
    const url = new URL('/api/dashboard_medal_ratio.php', location.origin);
    if (leagueId) url.searchParams.set('league_id', leagueId);
    if (seasonId) url.searchParams.set('season_id', seasonId);
    url.searchParams.set('limit', '7');

    const { rows } = await fetch(url).then(r=>r.json());
    const labels = labelsOf(rows, 'team');
    const data   = numsOf(rows, 'goals');

    mkChart('#chMedalRatio', 'doughnut', labels, [
      { label:'Goals', data }
    ]);
  }

  // 2) 연도(시즌)별 평균 득점 라인
  {
    const url = new URL('/api/dashboard_yearly_performance.php', location.origin);
    if (leagueId) url.searchParams.set('league_id', leagueId);

    const { rows } = await fetch(url).then(r=>r.json());
    const labels = labelsOf(rows, 'season_name');
    const data   = numsOf(rows, 'avg_goals');

    mkChart('#chYearlyLine', 'line', labels, [
      { label:'Avg Goals per Match', data, tension: .3 }
    ], { scales:{ y:{ beginAtZero:true } } });
  }
})();
</script>


<?php include 'partials/footer.php'; ?>
