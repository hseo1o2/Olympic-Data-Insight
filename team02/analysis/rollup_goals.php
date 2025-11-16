<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$sql = "
SELECT *
FROM (
    SELECT 
        t.team_name AS team,
        COUNT(*) AS total_goals
    FROM match_events me
    JOIN teams t ON me.team_id = t.team_id
    WHERE me.event_type LIKE '%goal%'
    GROUP BY t.team_name WITH ROLLUP
) AS sub
ORDER BY 
    CASE WHEN team IS NULL THEN 1 ELSE 0 END,
    total_goals DESC;
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 style='text-align:center; color:#1d3557;'>⚽ Total Goals by Team (Event-based ROLLUP)</h3>

<table border='1' cellpadding='8' cellspacing='0'
       style='margin:auto; width:60%; text-align:center; background:white;'>
    <tr style='background:#1d3557; color:white;'>
        <th>Team</th>
        <th>Total Goals</th>
    </tr>

    <?php foreach ($rows as $row): ?>
        <?php
        // NULL → ROLLUP의 TOTAL 행 변환
        $team = $row['team'] ?? 'TOTAL';
        ?>
        <tr>
            <td><b><?= htmlspecialchars($team) ?></b></td>
            <td><?= $row['total_goals'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p style='text-align:center; color:#6c757d;'>* Based on match_events goal events (GROUP BY ... WITH ROLLUP)</p>

<?php include '../partials/footer.php'; ?>
