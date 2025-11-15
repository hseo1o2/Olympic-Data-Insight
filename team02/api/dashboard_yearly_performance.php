<?php
// api/dashboard_yearly_performance.php
require_once __DIR__ . '/../includes/db.php';

$league = $_GET['league_id'] ?? null; // 선택사항

// 시즌(연도)별 평균 득점(리그 전체)
$sql = "
  SELECT s.season_id, s.season_name,
         ROUND(AVG(m.home_goals + m.away_goals), 2) AS avg_goals
  FROM matches m
  JOIN seasons s ON s.season_id = m.season_id
  JOIN leagues l ON l.league_id = (SELECT league_id FROM seasons WHERE season_id = m.season_id)
  WHERE (:league IS NULL OR l.league_id = :league)
  GROUP BY s.season_id, s.season_name
  ORDER BY s.season_id
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':league', $league !== null ? $league : null, $league !== null ? PDO::PARAM_INT : PDO::PARAM_NULL);
$stmt->execute();
$rows = $stmt->fetchAll();

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['ok'=>true, 'rows'=>$rows], JSON_UNESCAPED_UNICODE);
