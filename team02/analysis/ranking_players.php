<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$sql = "
SELECT 
    p.player_name,
    t.team_name,
    COUNT(*) AS total_goals,
    RANK() OVER (ORDER BY COUNT(*) DESC) AS ranking
FROM match_events e
JOIN players p ON e.player_id = p.player_id
JOIN teams t ON e.team_id = t.team_id  
WHERE e.event_type IN ('goal', 'penalty_goal')
GROUP BY p.player_id, p.player_name, t.team_name
ORDER BY ranking;
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body { font-family: 'Segoe UI', Arial, sans-serif; background-color:#f8fafc; color:#222; }
h3 { text-align:center; color:#1d3557; margin-top:15px; }
table {
  width:80%; margin:25px auto;
  border-collapse:collapse; background:white;
  border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.08);
}
th {
  background-color:#1d3557;
  color:white;
  padding:10px;
}
td {
  text-align:center;
  padding:9px;
  border-bottom:1px solid #eee;
}
tr:hover { background-color:#f2f6fb; }
.notice {
  text-align:center;
  color:#6c757d;
  margin-top:10px;
  font-size:14px;
}
</style>

<h3>⚽ Premier League 24–25 — Player Ranking by Goals</h3>

<table>
<tr>
  <th>Rank</th>
  <th>Player</th>
  <th>Team</th>
  <th>Total Goals</th>
</tr>

<?php if (count($rows) === 0): ?>
<tr><td colspan="4">No goal data available.</td></tr>
<?php else: ?>
<?php foreach ($rows as $r): ?>
<?php
switch ($r['ranking']) {
    case 1: $emoji = '🥇'; break;
    case 2: $emoji = '🥈'; break;
    case 3: $emoji = '🥉'; break;
    default: $emoji = $r['ranking']; break;
}
?>
<tr>
  <td><?= $emoji ?></td>
  <td><?= htmlspecialchars($r['player_name']) ?></td>
  <td><?= htmlspecialchars($r['team_name']) ?></td>
  <td><?= $r['total_goals'] ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</table>

<p class="notice">
  * Includes <b>goal</b> and <b>penalty_goal</b> events only.<br>
  * Uses <b>RANK() OVER</b> for ranking.
</p>

<!-- 📊 Chart.js Ranking (수정된 부분) -->
<div style="width:70%; max-width:900px; margin:30px auto; height:450px;">
    <canvas id="chartPlayerRank"></canvas>
</div>

<script>
const rankLabels = <?= json_encode(array_map(fn($r)=> $r['player_name'], $rows)) ?>;
const rankGoals  = <?= json_encode(array_map(fn($r)=> intval($r['total_goals']), $rows)) ?>;

mkChart("#chartPlayerRank", "bar",
  rankLabels,
  [{
    label: "Goals",
    data: rankGoals,
    backgroundColor: "rgba(153,102,255,0.4)",
    borderColor: "rgba(153,102,255,1)",
    borderWidth: 1
  }],
  {
    indexAxis: "y",
    plugins:{ title:{ display:true, text:"Top Scorers Ranking" }},
    scales: { x: { beginAtZero:true } }
  }
);
</script>

<?php include '../partials/footer.php'; ?>
