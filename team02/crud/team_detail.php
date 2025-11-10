<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

// 선택한 팀 ID 가져오기
$tid = $_GET['team_id'] ?? null;
if (!$tid) {
    echo "<p style='color:red;text-align:center;'>⚠️ Invalid team ID.</p>";
    include '../partials/footer.php';
    exit;
}

// 팀 분석 쿼리 실행
$sql = "
SELECT 
    t.team_name,
    COUNT(m.match_id) AS matches_played,
    SUM(CASE WHEN (m.home_team_id = t.team_id AND m.home_goals > m.away_goals)
               OR (m.away_team_id = t.team_id AND m.away_goals > m.home_goals)
        THEN 1 ELSE 0 END) AS wins,
    SUM(CASE WHEN m.home_goals = m.away_goals THEN 1 ELSE 0 END) AS draws,
    SUM(CASE WHEN (m.home_team_id = t.team_id AND m.home_goals < m.away_goals)
               OR (m.away_team_id = t.team_id AND m.away_goals < m.home_goals)
        THEN 1 ELSE 0 END) AS losses,
    SUM(CASE WHEN m.home_team_id = t.team_id THEN m.home_goals ELSE m.away_goals END) AS goals_for,
    SUM(CASE WHEN m.home_team_id = t.team_id THEN m.away_goals ELSE m.home_goals END) AS goals_against,
    ROUND(AVG(REPLACE(m.attendance, ',', '') * 1), 0) AS avg_attendance
FROM matches m
JOIN teams t ON (m.home_team_id = t.team_id OR m.away_team_id = t.team_id)
WHERE t.team_id = :tid
GROUP BY t.team_name;
";
$stmt = $pdo->prepare($sql);
$stmt->execute([':tid' => $tid]);
$team = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<style>
body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f8fafc; color: #222; }
.container {
  width: 60%;
  margin: 40px auto;
  background: white;
  border-radius: 12px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.08);
  padding: 25px 30px;
}
h2 {
  text-align: center;
  color: #1d3557;
  margin-bottom: 25px;
}
table {
  border-collapse: collapse;
  width: 100%;
  text-align: center;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
}
th {
  background-color: #1d3557;
  color: white;
  padding: 10px;
}
td {
  padding: 10px;
  border-bottom: 1px solid #eee;
}
.back {
  display: block;
  text-align: center;
  margin-top: 20px;
}
.back a {
  text-decoration: none;
  color: #457b9d;
  font-weight: bold;
}
.back a:hover {
  color: #e63946;
}
</style>

<div class="container">
<?php if ($team): ?>
  <h2>🏟 <?= htmlspecialchars($team['team_name']) ?> — Team Summary</h2>
  <table>
    <tr><th>Matches Played</th><td><?= $team['matches_played'] ?></td></tr>
    <tr><th>Wins</th><td><?= $team['wins'] ?></td></tr>
    <tr><th>Draws</th><td><?= $team['draws'] ?></td></tr>
    <tr><th>Losses</th><td><?= $team['losses'] ?></td></tr>
    <tr><th>Goals For</th><td><?= $team['goals_for'] ?></td></tr>
    <tr><th>Goals Against</th><td><?= $team['goals_against'] ?></td></tr>
    <tr><th>Average Attendance</th><td><?= number_format($team['avg_attendance']) ?></td></tr>
  </table>
<?php else: ?>
  <p style="text-align:center;color:red;">❌ No match data available for this team.</p>
<?php endif; ?>

  <div class="back">
    <a href="../crud/teams.php">← Back to Team List</a>
  </div>
</div>

<?php include '../partials/footer.php'; ?>
