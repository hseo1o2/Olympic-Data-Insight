<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* -------------------------------------------------------
   🔒 isAdmin()이 auth.php 안에 없을 경우 대비한 fallback
------------------------------------------------------- */
if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['user']) &&
               isset($_SESSION['user']['role']) &&
               $_SESSION['user']['role'] === 'admin';
    }
}

/* -------------------------------------------------------
   관리자 여부 확인
------------------------------------------------------- */
$isAdmin = isAdmin();

/* -------------------------------------------------------
   페이지네이션
------------------------------------------------------- */
$perPage = 30;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $perPage;

/* -------------------------------------------------------
   팀 목록
------------------------------------------------------- */
$teams = $pdo->query("SELECT team_id, team_name FROM teams ORDER BY team_name")->fetchAll();

/* -------------------------------------------------------
   팀별 필터
------------------------------------------------------- */
$selectedTeam = isset($_GET['team_id']) ? (int)$_GET['team_id'] : 0;

/* -------------------------------------------------------
   총 플레이어 수
------------------------------------------------------- */
if ($selectedTeam > 0) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM players WHERE team_id=:tid");
    $stmt->execute([':tid' => $selectedTeam]);
    $total = $stmt->fetchColumn();
} else {
    $total = $pdo->query("SELECT COUNT(*) FROM players")->fetchColumn();
}
$totalPages = ceil($total / $perPage);

/* -------------------------------------------------------
   플레이어 목록
------------------------------------------------------- */
if ($selectedTeam > 0) {
    $sql = "
        SELECT p.player_id, p.player_name, p.team_id, t.team_name
        FROM players p
        LEFT JOIN teams t ON p.team_id = t.team_id
        WHERE p.team_id = :tid
        ORDER BY p.player_id ASC
        LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':tid', $selectedTeam, PDO::PARAM_INT);
} else {
    $sql = "
        SELECT p.player_id, p.player_name, p.team_id, t.team_name
        FROM players p
        LEFT JOIN teams t ON p.team_id = t.team_id
        ORDER BY p.player_id ASC
        LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$players = $stmt->fetchAll();

/* -------------------------------------------------------
   관리자 전용 CRUD
------------------------------------------------------- */
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // 추가
    if (isset($_POST['add_player'])) {
        $name = trim($_POST['player_name']);
        $team = $_POST['team_id'];
        if ($name !== '' && $team) {
            $stmt = $pdo->prepare("INSERT INTO players (player_name, team_id) VALUES (:n, :t)");
            $stmt->execute([':n' => $name, ':t' => $team]);
            echo "<p style='color:green;text-align:center;'>✅ Player added successfully!</p>";
        }
    }

    // 수정
    if (isset($_POST['edit_player'])) {
        $id = $_POST['player_id'];
        $name = trim($_POST['player_name']);
        $team = $_POST['team_id'];
        $stmt = $pdo->prepare("UPDATE players SET player_name=:n, team_id=:t WHERE player_id=:id");
        $stmt->execute([':n' => $name, ':t' => $team, ':id' => $id]);
        echo "<p style='color:blue;text-align:center;'>✏️ Player updated successfully!</p>";
    }

    // 삭제
    if (isset($_POST['delete_player'])) {
        $id = $_POST['player_id'];
        $stmt = $pdo->prepare("DELETE FROM players WHERE player_id=:id");
        $stmt->execute([':id' => $id]);
        echo "<p style='color:red;text-align:center;'>🗑 Player deleted successfully!</p>";
    }
}
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
table { border-collapse:collapse;width:90%;margin:auto;background:white;
        box-shadow:0 2px 5px rgba(0,0,0,0.08);border-radius:8px;overflow:hidden; }
th { background-color:#1d3557;color:white;padding:10px; }
td { text-align:center;padding:9px;border-bottom:1px solid #eee; }
tr:hover { background-color:#f2f6fb; }
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
.modal input, .modal select { width:80%; margin-bottom:10px; }
</style>

<h3>👟 Player Management</h3>

<!-- 팀별 필터 -->
<form method="GET">
  <label for="team_id">Team:</label>
  <select name="team_id" id="team_id" onchange="this.form.submit()">
    <option value="">-- All Teams --</option>
    <?php foreach($teams as $t): ?>
      <option value="<?= $t['team_id'] ?>" <?= $t['team_id']==$selectedTeam?'selected':'' ?>>
        <?= htmlspecialchars($t['team_name']) ?>
      </option>
    <?php endforeach; ?>
  </select>
</form>

<!-- 관리자 전용: 추가 버튼 -->
<?php if ($isAdmin): ?>
<div style="text-align:center;margin-bottom:10px;">
  <button onclick="openAddModal()">＋ Add Player</button>
</div>
<?php endif; ?>

<?php
// 🔎 캡션용 텍스트 계산 (옛날 PHP도 안전하게)
$captionText = "Showing all players";
if ($selectedTeam) {
    $teamNameMap = array();
    foreach ($teams as $t) {
        $teamNameMap[$t['team_id']] = $t['team_name'];
    }
    if (isset($teamNameMap[$selectedTeam])) {
        $captionText = "Showing players from <b>" . htmlspecialchars($teamNameMap[$selectedTeam]) . "</b>";
    }
}
?>
<p class="caption">
<?php 
$teamNames = array_column($teams, 'team_name', 'team_id');

if ($selectedTeam && isset($teamNames[$selectedTeam])) {
    echo "Showing players from <b>" . htmlspecialchars($teamNames[$selectedTeam]) . "</b>";
} else {
    echo "Showing all players";
}

echo " — Page {$page} of {$totalPages} ({$total} total)";
?>
</p>

<table>
<tr>
  <th>No.</th>
  <th>Player Name</th>
  <th>Team</th>
  <?php if ($isAdmin): ?><th>Actions</th><?php endif; ?>
</tr>

<?php 
$displayIndex = $offset + 1;
foreach($players as $p): ?>
<tr>
  <td><?= $displayIndex++ ?></td>
  <td><?= htmlspecialchars($p['player_name']) ?></td>
  <td><?= htmlspecialchars($p['team_name'] ?? '—') ?></td>
  <?php if ($isAdmin): ?>
  <td class="action-buttons">
    <button class="edit-btn"
      onclick="openEditModal(
        <?= $p['player_id'] ?>,
        '<?= htmlspecialchars($p['player_name'], ENT_QUOTES) ?>',
        <?= $p['team_id'] ?? 'null' ?>
      )">✏️ Edit</button>
    <button class="delete-btn" onclick="openDeleteModal(<?= $p['player_id'] ?>)">🗑 Delete</button>
  </td>
  <?php endif; ?>
</tr>
<?php endforeach; ?>
</table>

<div class="pagination">
<?php for($i=1; $i<=$totalPages; $i++): ?>
  <a href="?team_id=<?= $selectedTeam ?>&page=<?= $i ?>" class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

<!-- 모달 섹션 -->
<?php if ($isAdmin): ?>
  
<!-- Add Modal -->
<div id="addModal" class="modal">
  <div class="modal-content">
    <h4>➕ Add Player</h4>
    <form method="POST">
      <input type="text" name="player_name" placeholder="Player name..." required><br>
      <select name="team_id" required>
        <option value="">Select team</option>
        <?php foreach($teams as $t): ?>
          <option value="<?= $t['team_id'] ?>"><?= htmlspecialchars($t['team_name']) ?></option>
        <?php endforeach; ?>
      </select><br>
      <button type="submit" name="add_player">Add</button>
      <button type="button" onclick="closeModal('addModal')">Cancel</button>
    </form>
  </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h4>✏️ Edit Player</h4>
    <form method="POST">
      <input type="hidden" name="player_id" id="edit_id">
      <input type="text" name="player_name" id="edit_name" required><br>
      <select name="team_id" id="edit_team" required>
        <?php foreach($teams as $t): ?>
          <option value="<?= $t['team_id'] ?>"><?= htmlspecialchars($t['team_name']) ?></option>
        <?php endforeach; ?>
      </select><br>
      <button type="submit" name="edit_player">Save</button>
      <button type="button" onclick="closeModal('editModal')">Cancel</button>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <h4>🗑 Delete Player</h4>
    <p>Are you sure you want to delete this player?</p>
    <form method="POST">
      <input type="hidden" name="player_id" id="delete_id">
      <button type="submit" name="delete_player" style="background:#e63946;">Delete</button>
      <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
    </form>
  </div>
</div>
<?php endif; ?>

<script>
function openAddModal() {
  document.getElementById('addModal').style.display = 'flex';
}
function openEditModal(id, name, currentTeamId) {
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_name').value = name;

  var teamSelect = document.getElementById('edit_team');
  for (var i = 0; i < teamSelect.options.length; i++) {
    if (parseInt(teamSelect.options[i].value) === currentTeamId) {
      teamSelect.selectedIndex = i;
      break;
    }
  }

  document.getElementById('editModal').style.display = 'flex';
}
function openDeleteModal(id) {
  document.getElementById('delete_id').value = id;
  document.getElementById('deleteModal').style.display = 'flex';
}
function closeModal(id) {
  document.getElementById(id).style.display = 'none';
}
</script>

<?php include '../partials/footer.php'; ?>
