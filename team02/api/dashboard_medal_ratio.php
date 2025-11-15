<?php
// api/dashboard_medal_ratio.php
require_once __DIR__ . '/../includes/db.php';

$league = $_GET['league_id'] ?? null;   // 선택사항
$season = $_GET['season_id'] ?? null;   // 선택사항
$limit  = intval($_GET['limit'] ?? 7);  // 상위 N팀 + Others

// 홈/원정을 단일 시계열로 펼친 뒤 팀별 득점 합
$sql = "
  SELECT t.team_name AS team, SUM(x.goals) AS goals
  FROM (
    SELECT m.season_id, m.home_team_id AS team_id, m.home_goals AS goals
    FROM matches m
    UNION ALL
    SELECT m.season_id, m.away_team_id, m.away_goals
    FROM matches m
  ) x
  JOIN teams t   ON t.team_id = x.team_id
  JOIN seasons s ON s.season_id = x.season_id
  JOIN leagues l ON l.league_id = (SELECT league_id FROM seasons WHERE season_id = x.season_id)
  WHERE (:league IS NULL OR l.league_id = :league)
    AND (:season IS NULL OR s.season_id = :season)
  GROUP BY t.team_id, t.team_name
  ORDER BY goals DESC
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':league', $league !== null ? $league : null, $league !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
$stmt->bindValue(':season', $season !== null ? $season : null, $season !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
$stmt->execute();
$rows = $stmt->fetchAll();

$total = array_sum(array_column($rows, 'goals'));

$top = array_slice($rows, 0, $limit);
$othersGoals = max(0, $total - array_sum(array_column($top, 'goals')));
if ($othersGoals > 0) {
  $top[] = ['team' => 'Others', 'goals' => $othersGoals];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok'=>true, 'total'=>$total, 'rows'=>$top], JSON_UNESCAPED_UNICODE);
