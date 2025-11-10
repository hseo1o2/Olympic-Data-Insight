<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$sql = "
SELECT 
    t.team_name,
    m.match_date,
    (m.home_goals + m.away_goals) AS goals,
    ROUND(AVG(m.home_goals + m.away_goals)
        OVER (PARTITION BY t.team_id ORDER BY m.match_date ROWS BETWEEN 4 PRECEDING AND CURRENT ROW), 2)
        AS moving_avg
FROM matches m
JOIN teams t ON m.home_team_id = t.team_id
ORDER BY t.team_name, m.match_date;
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 style='text-align:center; color:#1d3557;'>📈 5-Match Moving Average of Goals</h3>

<table border='1' cellpadding='8' cellspacing='0' style='margin:auto; width:80%; background:white;text-align:center;'>
<tr style='background:#1d3557;color:white;'>
  <th>Team</th><th>Date</th><th>Goals</th><th>5-Match Avg</th>
</tr>

<?php foreach($rows as $r): ?>
<tr>
  <td><?= htmlspecialchars($r['team_name']) ?></td>
  <td><?= $r['match_date'] ?></td>
  <td><?= $r['goals'] ?></td>
  <td><b><?= $r['moving_avg'] ?></b></td>
</tr>
<?php endforeach; ?>
</table>

<p style='text-align:center;color:#6c757d;'>* Uses ROWS BETWEEN 4 PRECEDING AND CURRENT ROW</p>

<?php include '../partials/footer.php'; ?>
