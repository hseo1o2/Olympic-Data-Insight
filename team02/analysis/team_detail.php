<?php
 /**
 * SQL: 김서연
 * Backend: 김현영
 * Frontend: 장현서, 강민경
 */
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

// GET에서 팀 ID 받기
$tid = $_GET['team_id'] ?? null;
if (!$tid) {
    echo "<p style='color:red;text-align:center;'>⚠️ Invalid team ID.</p>";
    include '../partials/footer.php';
    exit;
}

// 홈/원정 필터 (기본값: 전체)
$type = $_GET['type'] ?? 'all';

$extra = "";
if ($type === "home") {
    $extra = " AND m.home_team_id = t.team_id ";
} elseif ($type === "away") {
    $extra = " AND m.away_team_id = t.team_id ";
}

// 팀의 홈/원정 기록 요약
$sql = "
SELECT
    t.team_name,
    CASE
        WHEN m.home_team_id = t.team_id THEN 'Home'
        ELSE 'Away'
    END AS location_type,
    COUNT(*) AS matches_played,
    SUM(
        CASE 
            WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals)
              OR (m.away_team_id = t.team_id AND m.away_goals > m.home_goals)
        THEN 1 ELSE 0 END
    ) AS wins,
    SUM(CASE WHEN m.home_goals = m.away_goals THEN 1 ELSE 0 END) AS draws,
    SUM(
        CASE 
            WHEN (m.home_team_id = t.team_id AND m.home_goals < m.away_goals)
              OR (m.away_team_id = t.team_id AND m.away_goals < m.home_goals)
        THEN 1 ELSE 0 END
    ) AS losses,
    SUM(
        CASE WHEN m.home_team_id = t.team_id THEN m.home_goals ELSE m.away_goals END
    ) AS goals_for,
    SUM(
        CASE WHEN m.home_team_id = t.team_id THEN m.away_goals ELSE m.home_goals END
    ) AS goals_against,
    ROUND(AVG(REPLACE(m.attendance, ',', '') * 1), 0) AS avg_attendance
FROM matches m
JOIN teams t 
    ON (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)
WHERE t.team_id = :tid
$extra
GROUP BY t.team_name, location_type
ORDER BY location_type DESC;
";

$stmt = $pdo->prepare($sql);
$stmt->execute([':tid' => $tid]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color:#222; }
.container {
  width: 60%;
  margin: 40px auto;
  background: white;
  border-radius: 12px;
  padding: 25px 30px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}
h2 { text-align:center; color:#1d3557; margin-bottom:20px; }
table {
  width:100%; border-collapse:collapse; margin-top:20px;
  background:white; border-radius:8px; overflow:hidden;
}
th { background:#1d3557; color:white; padding:10px; }
td { padding:10px; border-bottom:1px solid #eee; text-align:center; }
.filter-box { text-align:center; margin-bottom:20px; }
.back { text-align:center; margin-top:20px; }
.back a { color:#457b9d; text-decoration:none; font-weight:bold; }
.back a:hover { color:#e63946; }
</style>

<div class="container">

<form method="GET" class="filter-box">
    <input type="hidden" name="team_id" value="<?= $tid ?>">
    <label><b>Match Type:</b></label>
    <select name="type">
        <option value="all" <?= ($type === 'all') ? 'selected' : '' ?>>All</option>
        <option value="home" <?= ($type === 'home') ? 'selected' : '' ?>>Home Only</option>
        <option value="away" <?= ($type === 'away') ? 'selected' : '' ?>>Away Only</option>
    </select>
    <button type="submit" style="padding:6px 12px; margin-left:10px;">Apply</button>
</form>

<?php
if (!$rows) {
    echo "<p style='text-align:center;color:red;'>❌ No match data available.</p>";
} else {
    echo "<h2>🏟 " . htmlspecialchars($rows[0]['team_name']) . " — Team Summary</h2>";

    $sumRow = null;
    if ($type === "all") {
        $sumRow = [
            'matches_played' => 0,
            'wins' => 0,
            'draws' => 0,
            'losses' => 0,
            'goals_for' => 0,
            'goals_against' => 0,
            'avg_attendance' => 0
        ];

        foreach ($rows as $r) {
            $sumRow['matches_played'] += $r['matches_played'];
            $sumRow['wins'] += $r['wins'];
            $sumRow['draws'] += $r['draws'];
            $sumRow['losses'] += $r['losses'];
            $sumRow['goals_for'] += $r['goals_for'];
            $sumRow['goals_against'] += $r['goals_against'];
            $sumRow['avg_attendance'] += $r['avg_attendance'];
        }

        $avgAttendance = round($sumRow['avg_attendance'] / count($rows));
    }
?>

<table>
<tr>
  <?php if ($type !== "all"): ?>
    <th>Location</th>
  <?php endif; ?>
  <th>Matches</th>
  <th>Wins</th>
  <th>Draws</th>
  <th>Losses</th>
  <th>Goals For</th>
  <th>Goals Against</th>
  <th>Avg Attendance</th>
</tr>

<?php if ($type === "all"): ?>
<tr>
  <td><?= $sumRow['matches_played'] ?></td>
  <td><?= $sumRow['wins'] ?></td>
  <td><?= $sumRow['draws'] ?></td>
  <td><?= $sumRow['losses'] ?></td>
  <td><?= $sumRow['goals_for'] ?></td>
  <td><?= $sumRow['goals_against'] ?></td>
  <td><?= number_format($avgAttendance) ?></td>
</tr>

<?php else: ?>
<?php foreach ($rows as $team): ?>
<tr>
  <td><?= $team['location_type'] ?></td>
  <td><?= $team['matches_played'] ?></td>
  <td><?= $team['wins'] ?></td>
  <td><?= $team['draws'] ?></td>
  <td><?= $team['losses'] ?></td>
  <td><?= $team['goals_for'] ?></td>
  <td><?= $team['goals_against'] ?></td>
  <td><?= number_format($team['avg_attendance']) ?></td>
</tr>
<?php endforeach; ?>
<?php endif; ?>

</table>

<?php } ?>

<div class="back">
  <a href="../crud/teams.php">← Back to Team List</a>
</div>

</div>

<?php include '../partials/footer.php'; ?>
