<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$isAdmin = isAdmin();

// 페이지네이션 설정
$perPage = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// 정렬 기준
$sort = $_GET['sort'] ?? 'desc';
$orderBy = ($sort === 'asc') ? 'ASC' : 'DESC';

// 경기 삭제 처리 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_match_id'])) {
    $match_id = $_POST['delete_match_id'];

    // 먼저 해당 경기의 이벤트 삭제
    $stmt = $pdo->prepare("DELETE FROM match_events WHERE match_id = :mid");
    $stmt->execute([':mid' => $match_id]);

    // 경기 삭제
    $stmt = $pdo->prepare("DELETE FROM matches WHERE match_id = :mid");
    $stmt->execute([':mid' => $match_id]);

    echo "<p style='text-align:center;color:red;font-weight:bold;'>Match deleted successfully.</p>";
}

// 총 경기 수 (페이지네이션 계산용)
$total = $pdo->query("SELECT COUNT(*) FROM matches")->fetchColumn();
$totalPages = ceil($total / $perPage);

// 경기 목록 조회
$sql = "
SELECT 
    m.match_id, m.match_date, m.home_goals, m.away_goals,
    t1.team_name AS home_team, 
    t2.team_name AS away_team,
    r.referee_name
FROM matches m
JOIN teams t1 ON m.home_team_id = t1.team_id
JOIN teams t2 ON m.away_team_id = t2.team_id
LEFT JOIN referees r ON m.referee_id = r.referee_id
ORDER BY m.match_date $orderBy, m.match_id $orderBy
LIMIT :limit OFFSET :offset
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$matches = $stmt->fetchAll();
?>

<style>
body { font-family:'Segoe UI',Arial,sans-serif;background:#f8fafc;color:#222; }

h3 { 
  color:#1d3557;
  text-align:center;
  margin: 25px 0 10px 0;
  font-size:26px;
}

table {
  border-collapse:collapse;
  width:95%;
  margin:auto;
  background:white;
  box-shadow:0 2px 5px rgba(0,0,0,0.08);
  border-radius:10px;
  overflow:hidden;
}

th {
  background:#1d3557;
  color:white;
  padding:10px;
  font-size:15px;
}

td {
  text-align:center;
  padding:10px;
  border-bottom:1px solid #eee;
}

tr:hover { background:#f2f6fb; }

.action-btn {
  padding:5px 10px;
  border-radius:5px;
  font-size:13px;
  color:white;
  text-decoration:none;
}

.detail-btn { background:#2a9d8f; }
.detail-btn:hover { background:#38b2a3; }

.delete-btn { background:#e63946; border:none; cursor:pointer; }
delete-btn:hover { background:#ff6b6b; }

.pagination { text-align:center; margin-top:15px; }
.pagination a { padding:6px 10px; color:#1d3557; text-decoration:none; }
.pagination a.active { color:#e63946; font-weight:bold; }

.sort-links { text-align:center; margin-bottom:15px; }
.sort-links a { text-decoration:none; color:#1d3557; padding:0 8px; }
.sort-links a.active { font-weight:bold; color:#e63946; }
</style>

<h3>⚽ Match Management</h3>

<div class="sort-links">
  <a href="?sort=desc" class="<?= $sort=='desc'?'active':'' ?>">⬆ Newest First</a> |
  <a href="?sort=asc" class="<?= $sort=='asc'?'active':'' ?>">⬇ Oldest First</a>
</div>

<p style="text-align:center;color:#6c757d;">Showing page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> matches)</p>

<table>
<tr>
  <th>No.</th>
  <th>Date</th>
  <th>Home</th>
  <th>Score</th>
  <th>Away</th>
  <th>Referee</th>
  <th>Result</th>
  <th>Actions</th>
</tr>

<?php
$displayIndex = $offset + 1;

// 경기 리스트 출력
foreach ($matches as $m):
  if ($m['home_goals'] > $m['away_goals']) $result = "<span style='color:#2a9d8f;font-weight:bold;'>Home Win</span>";
  elseif ($m['home_goals'] < $m['away_goals']) $result = "<span style='color:#e63946;font-weight:bold;'>Away Win</span>";
  else $result = "<span style='color:#6c757d;'>Draw</span>";
?>
<tr>
  <td><?= $displayIndex++ ?></td>
  <td><?= htmlspecialchars($m['match_date']) ?></td>
  <td><?= htmlspecialchars($m['home_team']) ?></td>
  <td><b><?= $m['home_goals'] ?> - <?= $m['away_goals'] ?></b></td>
  <td><?= htmlspecialchars($m['away_team']) ?></td>
  <td><?= htmlspecialchars($m['referee_name'] ?? '-') ?></td>
  <td><?= $result ?></td>

  <td>
    <a class="action-btn detail-btn" href="edit_matches.php?match_id=<?= $m['match_id'] ?>">Details</a>

    <?php if ($isAdmin): ?>
    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this match?');">
        <input type="hidden" name="delete_match_id" value="<?= $m['match_id'] ?>">
        <button class="action-btn delete-btn">Delete</button>
    </form>
    <?php endif; ?>
  </td>
</tr>

<?php endforeach; ?>
</table>

<div class="pagination">
<?php for ($i=1; $i <= $totalPages; $i++): ?>
  <a href="?page=<?= $i ?>&sort=<?= $sort ?>" class="<?= $i==$page ? 'active' : '' ?>">
    <?= $i ?>
  </a>
<?php endfor; ?>
</div>

<?php include '../partials/footer.php'; ?>
