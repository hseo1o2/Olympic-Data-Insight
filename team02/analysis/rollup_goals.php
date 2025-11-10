<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$sql = "
SELECT 
    t.team_name AS team,
    SUM(m.home_goals + m.away_goals) AS total_goals
FROM matches m
JOIN teams t ON m.home_team_id = t.team_id
GROUP BY t.team_name WITH ROLLUP;
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 style='text-align:center; color:#1d3557;'>📊 Total Goals by Team (ROLLUP)</h3>

<table border='1' cellpadding='8' cellspacing='0' style='margin:auto; width:60%; text-align:center; background:white;'>
<tr style='background:#1d3557;color:white;'>
    <th>Team</th>
    <th>Total Goals</th>
</tr>

<?php foreach($rows as $row): ?>
<?php
$team = $row['team'] ?? 'TOTAL';
?>
<tr>
    <td><b><?= htmlspecialchars($team) ?></b></td>
    <td><?= $row['total_goals'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<p style='text-align:center;color:#6c757d;'>* Using GROUP BY ... WITH ROLLUP</p>

<?php include '../partials/footer.php'; ?>
