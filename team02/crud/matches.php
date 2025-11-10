<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

// 관리자 여부 확인
$isAdmin = isAdmin();

// 페이지네이션
$perPage = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

// 정렬 옵션 선택
$sort = $_GET['sort'] ?? 'desc';
$orderBy = ($sort === 'asc') ? 'ASC' : 'DESC';

// 총 경기 수
$total = $pdo->query("SELECT COUNT(*) FROM matches")->fetchColumn();
$totalPages = ceil($total / $perPage);

// 팀 목록 (드롭다운용)
$teams = $pdo->query("SELECT team_id, team_name FROM teams ORDER BY team_name")->fetchAll();

// 심판 목록 
$referees = $pdo->query("SELECT referee_id, referee_name FROM referees ORDER BY referee_name")->fetchAll();

// 관리자 전용 CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $isAdmin) {

    // 경기 추가
    if (isset($_POST['add_match'])) {
        $home = $_POST['home_team'];
        $away = $_POST['away_team'];
        $hg = $_POST['home_goals'];
        $ag = $_POST['away_goals'];
        $date = $_POST['match_date'] ?? date('Y-m-d');
        $ref = $_POST['referee_id'];

        if ($home !== $away) {
            $stmt = $pdo->prepare("
                INSERT INTO matches (home_team_id, away_team_id, home_goals, away_goals, match_date, referee_id)
                VALUES (:h, :a, :hg, :ag, :d, :r)
            ");
            $stmt->execute([':h'=>$home, ':a'=>$away, ':hg'=>$hg, ':ag'=>$ag, ':d'=>$date, ':r'=>$ref]);
            echo "<p style='color:green;text-align:center;'>✅ Match added successfully!</p>";
        } else {
            echo "<p style='color:red;text-align:center;'>⚠️ Home and Away teams cannot be the same!</p>";
        }
    }

    // 경기 수정
    if (isset($_POST['edit_match'])) {
        $id = $_POST['match_id'];
        $hg = $_POST['home_goals'];
        $ag = $_POST['away_goals'];
        $date = $_POST['match_date'];
        $ref = $_POST['referee_id'];
        $stmt = $pdo->prepare("
            UPDATE matches 
            SET home_goals=:hg, away_goals=:ag, match_date=:d, referee_id=:r 
            WHERE match_id=:id
        ");
        $stmt->execute([':hg'=>$hg, ':ag'=>$ag, ':d'=>$date, ':r'=>$ref, ':id'=>$id]);
        echo "<p style='color:blue;text-align:center;'>✏️ Match updated successfully!</p>";
    }

    // 경기 삭제
    if (isset($_POST['delete_match'])) {
        $id = $_POST['match_id'];
        $stmt = $pdo->prepare("DELETE FROM matches WHERE match_id=:id");
        $stmt->execute([':id'=>$id]);
        echo "<p style='color:red;text-align:center;'>🗑 Match deleted successfully!</p>";
    }
}

// 경기 목록 불러오기 
$sql = "
SELECT m.match_id, m.match_date,
       t1.team_name AS home_team, t2.team_name AS away_team,
       m.home_goals, m.away_goals,
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
body { font-family:'Segoe UI',Arial,sans-serif;background-color:#f8fafc;color:#222; }
h3 { color:#1d3557;text-align:center;margin-top:10px; }
form { margin-bottom:25px;text-align:center; }
select,input,button {
  padding:7px;margin:4px;border-radius:6px;border:1px solid #ccc;
}
button { background-color:#1d3557;color:white;border:none;cursor:pointer; }
button:hover { background-color:#457b9d; }
.sort-links { text-align:center;margin-bottom:10px; }
.sort-links a { color:#1d3557;text-decoration:none;margin:0 10px; }
.sort-links a.active { font-weight:bold;color:#e63946; }
table { border-collapse:collapse;width:95%;margin:auto;background:white;
        box-shadow:0 2px 5px rgba(0,0,0,0.08);border-radius:8px;overflow:hidden; }
th { background-color:#1d3557;color:white;padding:10px; }
td { text-align:center;padding:9px;border-bottom:1px solid #eee; }
tr:hover { background-color:#f2f6fb; }
.score { font-weight:bold; }
.win { color:#2a9d8f;font-weight:bold; }
.loss { color:#e63946;font-weight:bold; }
.draw { color:#6c757d; }
.pagination { margin-top:20px;text-align:center; }
.pagination a { padding:6px 10px;text-decoration:none;color:#1d3557; }
.pagination a.active { font-weight:bold;color:#e63946; }
.caption { text-align:center;color:#6c757d;margin-bottom:10px; }
.action-buttons button { margin:2px; padding:4px 8px; font-size:13px; border-radius:5px; }
.edit-btn { background:#457b9d; }
.delete-btn { background:#e63946; }
.modal {
  display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%;
  background:rgba(0,0,0,0.5); justify-content:center; align-items:center;
}
.modal-content {
  background:white; padding:20px; border-radius:10px; width:350px; text-align:center;
}
.modal input { width:80%; margin-bottom:10px; }
</style>

<h3>🏟 Match Management</h3>

<div class="sort-links">
  <a href="?sort=desc" class="<?= $sort=='desc'?'active':'' ?>">⬆ Newest First</a> |
  <a href="?sort=asc" class="<?= $sort=='asc'?'active':'' ?>">⬇ Oldest First</a>
</div>

<!-- 관리자 전용: 경기 추가 폼 -->
<?php if ($isAdmin): ?>
<form method="POST">
  <label>🏠 Home:</label>
  <select name="home_team" required>
    <?php foreach($teams as $t): ?>
      <option value="<?= $t['team_id'] ?>"><?= htmlspecialchars($t['team_name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>⚔️ Away:</label>
  <select name="away_team" required>
    <?php foreach($teams as $t): ?>
      <option value="<?= $t['team_id'] ?>"><?= htmlspecialchars($t['team_name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>🏆 Score:</label>
  <input type="number" name="home_goals" min="0" required> -
  <input type="number" name="away_goals" min="0" required>

  <label>📅 Date:</label>
  <input type="date" name="match_date" value="<?= date('Y-m-d') ?>">

  <label>👨‍⚖️ Referee:</label>
  <select name="referee_id" required>
    <?php foreach($referees as $r): ?>
      <option value="<?= $r['referee_id'] ?>"><?= htmlspecialchars($r['referee_name']) ?></option>
    <?php endforeach; ?>
  </select>

  <button type="submit" name="add_match">Add Match</button>
</form>
<?php else: ?>
<p style="text-align:center;color:#6c757d;">
  👀 You can view all matches below, but adding or editing is for admins only.
</p>
<?php endif; ?>

<p class="caption">Showing page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> matches total)</p>

<table>
<tr>
  <th>No.</th>
  <th>Date</th>
  <th>Home</th>
  <th>Score</th>
  <th>Away</th>
  <th>Referee</th> 
  <th>Result</th>  
  <?php if ($isAdmin): ?><th>Actions</th><?php endif; ?>
</tr>

<?php 
$displayIndex = $offset + 1;
foreach($matches as $m): 
  if ($m['home_goals'] > $m['away_goals']) $result = "<span class='win'>Home Win</span>";
  elseif ($m['home_goals'] < $m['away_goals']) $result = "<span class='loss'>Away Win</span>";
  else $result = "<span class='draw'>Draw</span>";
?>
<tr>
  <td><?= $displayIndex++ ?></td>
  <td><?= htmlspecialchars($m['match_date']) ?></td>
  <td><?= htmlspecialchars($m['home_team']) ?></td>
  <td class="score"><?= $m['home_goals'] ?> - <?= $m['away_goals'] ?></td>
  <td><?= htmlspecialchars($m['away_team']) ?></td>
  <td><?= htmlspecialchars($m['referee_name'] ?? '-') ?></td> 
  <td><?= $result ?></td> 
  <?php if ($isAdmin): ?>
  <td class="action-buttons">
    <button class="edit-btn" onclick="openEditModal(<?= $m['match_id'] ?>, '<?= $m['match_date'] ?>', <?= $m['home_goals'] ?>, <?= $m['away_goals'] ?>)">✏️ Edit</button>
    <button class="delete-btn" onclick="openDeleteModal(<?= $m['match_id'] ?>)">🗑 Delete</button>
  </td>
  <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<div class="pagination">
<?php for($i=1; $i<=$totalPages; $i++): ?>
  <a href="?page=<?= $i ?>&sort=<?= $sort ?>" class="<?= $i==$page ? 'active' : '' ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

<?php include '../partials/footer.php'; ?>
