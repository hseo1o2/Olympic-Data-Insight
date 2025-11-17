<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$teams = $pdo->query("SELECT team_name FROM teams ORDER BY team_name")
             ->fetchAll(PDO::FETCH_COLUMN);

$selectedTeam = $_GET['team'] ?? '';

$sql = "
SELECT 
    t.team_name,
    m.match_date,
    CASE 
        WHEN t.team_id = m.home_team_id THEN m.home_goals
        ELSE m.away_goals
    END AS goals,
    ROUND(
        AVG(
            CASE 
                WHEN t.team_id = m.home_team_id THEN m.home_goals
                ELSE m.away_goals
            END
        ) OVER (
            PARTITION BY t.team_id 
            ORDER BY m.match_date 
            ROWS BETWEEN 4 PRECEDING AND CURRENT ROW
        ), 
    2
    ) AS moving_avg
FROM matches m
JOIN teams t 
    ON t.team_id = m.home_team_id OR t.team_id = m.away_team_id
";

$params = [];
if (!empty($selectedTeam)) {
    $sql .= " WHERE t.team_name = :team ";
    $params['team'] = $selectedTeam;
}

$sql .= " ORDER BY t.team_name, m.match_date;";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3 style='text-align:center; color:#1d3557;'>📈 5-Match Moving Average of Goals</h3>

<form method="GET" style="text-align:center; margin-bottom:10px;">
    <select name="team">
        <option value="">-- All Teams --</option>
        <?php foreach ($teams as $t): ?>
            <option value="<?= htmlspecialchars($t) ?>"
                <?= ($selectedTeam === $t ? 'selected' : '') ?>>
                <?= htmlspecialchars($t) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Search</button>
</form>

<table border='1' cellpadding='8' cellspacing='0'
       style='margin:auto; width:80%; background:white;text-align:center;'>
<tr style='background:#1d3557;color:white;'>
  <th>Team</th>
  <th>Date</th>
  <th>Goals</th>
  <th>5-Match Avg</th>
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


<!-- 📊 Chart.js Windowing -->
<?php if (!empty($selectedTeam)): ?>
    <div style="width:70%; max-width:900px; margin:30px auto; height:420px;">
        <canvas id="chartWindow"></canvas>
    </div>

    <script>
    const windowLabels = <?= json_encode(array_map(fn($r)=> $r['match_date'], $rows)) ?>;
    const windowGoals  = <?= json_encode(array_map(fn($r)=> intval($r['goals']), $rows)) ?>;
    const windowAvg    = <?= json_encode(array_map(fn($r)=> floatval($r['moving_avg']), $rows)) ?>;

    mkChart("#chartWindow", "line",
      windowLabels,
      [
        {
          label: "Goals",
          data: windowGoals,
          borderColor:"rgba(54,162,235,1)",
          backgroundColor:"rgba(54,162,235,0.2)",
          fill:false
        },
        {
          label: "5-Match Moving Avg",
          data: windowAvg,
          borderColor:"rgba(255,99,132,1)",
          backgroundColor:"rgba(255,99,132,0.2)",
          borderDash:[5,5],
          fill:false
        }
      ],
      {
        plugins:{ title:{ display:true, text:"5-Match Moving Average — <?= htmlspecialchars($selectedTeam) ?>" }},
        scales:{ y:{ beginAtZero:true }}
      }
    );
    </script>

<?php else: ?>
    <p style="text-align:center; color:#6c757d; margin-top:20px;">
       👉 Please select a team to view the moving average chart.
    </p>
<?php endif; ?>

<?php include '../partials/footer.php'; ?>
