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
            WHEN (m.home_goals = m.away_goals AND (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)) THEN 1
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

.top1 td {
  font-weight: 700;
  color: #1d3557;
}

.caption {
  text-align: center; color: #6c757d; font-size: 14px; margin-top: 15px;
}
</style>

<h3>🏅 Premier League 24–25 — Team Ranking by Points</h3>

<table>
  <tr>
    <th>Rank</th>
    <th>Team</th>
    <th>Points</th>
  </tr>
  <?php 
  $rank = 1;
  foreach($rows as $r): 
      $rowClass = ($rank == 1) ? 'top1' : '';
  ?>
  <tr class="<?= $rowClass ?>">
    <td><?= $rank ?></td>
    <td><?= htmlspecialchars($r['team']) ?></td>
    <td><?= $r['points'] ?? 0 ?></td>
  </tr>
  <?php $rank++; endforeach; ?>
</table>

<p class="caption">🏆 Ranking is based on match data (3 pts win, 1 pt draw, 0 pt loss)</p>

<?php include '../partials/footer.php'; ?>
