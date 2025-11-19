<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$sql = "
SELECT 
    t.team_name AS team,
    SUM(
        CASE 
            WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals) THEN 3
            WHEN (m.away_team_id = t.team_id AND m.away_goals > m.home_goals) THEN 3
            WHEN (m.home_goals = m.away_goals 
               AND (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)) THEN 1
            ELSE 0
        END
    ) AS points
FROM teams t
LEFT JOIN matches m 
  ON (t.team_id = m.home_team_id OR t.team_id = m.away_team_id)
GROUP BY t.team_id, t.team_name
ORDER BY points DESC
";

$rows = $pdo->query($sql)->fetchAll();
?>

<style>
body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color: #222; }
h3 { color: #1d3557; text-align: center; margin-top: 25px; }

table {
  width: 80%; margin: 30px auto; border-collapse: collapse;
  background: white; border-radius: 10px; overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
th {
  background-color: #1d3557; color: #fff; padding: 12px;
  text-transform: uppercase; font-size: 14px; letter-spacing: 0.5px;
}
td {
  padding: 10px; text-align: center; border-bottom: 1px solid #e9ecef;
  color: #1d3557;
}
tr:nth-child(even) { background-color: #f6f8fa; }
tr:nth-child(odd) { background-color: #ffffff; }
tr:hover { background-color: #edf2f7; }

.top1 td { font-weight: 700; color: #1d3557; }
.caption { text-align: center; color: #6c757d; font-size: 14px; margin-top: 15px; }
</style>

<h3>🏅 Premier League 24–25 — Team Ranking by Points</h3>

<table>
<tr>
  <th>Rank</th>
  <th>Team</th>
  <th>Points</th>
</tr>

<?php $i = 1; ?>
<?php foreach($rows as $r): ?>
<tr class="<?= ($i==1 ? 'top1' : '') ?>">
  <td><?= $i ?></td>
  <td><?= htmlspecialchars($r['team']) ?></td>
  <td><?= $r['points'] ?? 0 ?></td>
</tr>
<?php $i++; endforeach; ?>
</table>

<p class="caption">🏆 Ranking based on 3/1/0 point system.</p>

<!-- Chart.js -->
<div style="width:70%; max-width:900px; margin:30px auto; height:450px;">
    <canvas id="chartTeamRank"></canvas>
</div>

<script>
const teamRankLabels = <?= json_encode(array_map(fn($r)=> $r['team'], $rows)) ?>;
const teamPoints     = <?= json_encode(array_map(fn($r)=> intval($r['points']), $rows)) ?>;

mkChart("#chartTeamRank", "bar",
  teamRankLabels,
  [{
    label: "Points",
    data: teamPoints,
    backgroundColor: "rgba(255,159,64,0.4)",
    borderColor: "rgba(255,159,64,1)",
    borderWidth: 1
  }],
  {
    indexAxis: "y",
    plugins:{ title:{ display:true, text:"Team Points Ranking" }},
    scales: { x: { beginAtZero: true } }
  }
);
</script>

<?php include '../partials/footer.php'; ?>
