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
    <?php $team = $row['team'] ?? 'TOTAL'; ?>
    <tr>
        <td><b><?= htmlspecialchars($team) ?></b></td>
        <td><?= $row['total_goals'] ?></td>
    </tr>
<?php endforeach; ?>
</table>

<p style='text-align:center; color:#6c757d;'>* Based on match_events goal events (GROUP BY ... WITH ROLLUP)</p>


<!-- 📊 Chart.js ROLLUP (수정된 부분) -->
<div style="width:70%; max-width:900px; margin:30px auto; height:420px;">
    <canvas id="chartRollup"></canvas>
</div>

<script>
const rollupLabels = <?= json_encode(array_map(fn($r)=> $r['team'] ?? "TOTAL", $rows)) ?>;
const rollupData   = <?= json_encode(array_map(fn($r)=> intval($r['total_goals']), $rows)) ?>;

mkChart("#chartRollup", "bar",
  rollupLabels,
  [{
    label: "Total Goals",
    data: rollupData,
    backgroundColor: "rgba(54,162,235,0.4)",
    borderColor: "rgba(54,162,235,1)",
    borderWidth: 1
  }],
  {
    plugins: { title:{ display:true, text:"ROLLUP: Total Goals by Team" }},
    scales: { 
        y:{ beginAtZero:true }
    }
  }
);
</script>

<?php include '../partials/footer.php'; ?>
