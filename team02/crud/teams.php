<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

// 관리자 여부 확인
$isAdmin = isAdmin();

// 관리자 전용 CRUD
if ($isAdmin && $_SERVER['REQUEST_METHOD'] === 'POST') {

    // 팀 수정
    if (isset($_POST['edit_team'])) {
        $id = $_POST['team_id'];
        $name = trim($_POST['team_name']);
        if ($name !== '') {
            $stmt = $pdo->prepare("UPDATE teams SET team_name=:n WHERE team_id=:id");
            $stmt->execute([':n' => $name, ':id' => $id]);
            echo "<p style='color:blue;text-align:center;'>✏️ Team updated successfully!</p>";
        }
    }

    // 팀 삭제
    if (isset($_POST['delete_team'])) {
      $id = $_POST['team_id'];

      // 1) team_id 기반 match_events 삭제
      $stmt = $pdo->prepare("DELETE FROM match_events WHERE team_id = :id");
      $stmt->execute([':id' => $id]);

      // 2) player_id 기반 match_events 삭제
      $stmt = $pdo->prepare("
          DELETE FROM match_events 
          WHERE player_id IN (SELECT player_id FROM players WHERE team_id = :id)
      ");
      $stmt->execute([':id' => $id]);

      // 3) assist_player_id 기반 match_events 삭제
      $stmt = $pdo->prepare("
          DELETE FROM match_events 
          WHERE assist_player_id IN (SELECT player_id FROM players WHERE team_id = :id)
      ");
      $stmt->execute([':id' => $id]);

      // 4) match_id 기반 match_events 삭제
      $stmt = $pdo->prepare("
          DELETE FROM match_events
          WHERE match_id IN (
              SELECT match_id FROM matches
              WHERE home_team_id = :id OR away_team_id = :id
          )
      ");
      $stmt->execute([':id' => $id]);

      // 5) matches 삭제
      $stmt = $pdo->prepare("
          DELETE FROM matches
          WHERE home_team_id = :id OR away_team_id = :id
      ");
      $stmt->execute([':id' => $id]);

      // 6) players 삭제
      $stmt = $pdo->prepare("DELETE FROM players WHERE team_id = :id");
      $stmt->execute([':id' => $id]);

      // 7) team 삭제
      $stmt = $pdo->prepare("DELETE FROM teams WHERE team_id = :id");
      $stmt->execute([':id' => $id]);

      echo "<p style='color:red;text-align:center;'>🗑 Team deleted successfully!</p>";
    }
}

// 전체 팀 조회
$teams = $pdo->query("SELECT team_id, team_name FROM teams ORDER BY team_id ASC")->fetchAll();
?>

<style>
body { 
  font-family:'Segoe UI',Arial,sans-serif;
  background-color:#f8fafc;
  color:#222;
}
h3 { 
  color:#1d3557;
  text-align:center;
  margin-top:15px; 
  margin-bottom:25px;
}
table {
  width:70%;
  margin:auto;
  margin-bottom:60px; 
  border-collapse:collapse;
  background:white;
  border-radius:10px;
  box-shadow:0 2px 6px rgba(0,0,0,0.08);
  overflow:hidden;
}
th { background-color:#1d3557;color:white;padding:10px; }
td { text-align:center;padding:9px;border-bottom:1px solid #eee; }
tr:hover { background-color:#f2f6fb; }

button { padding:6px 10px;border:none;border-radius:5px;cursor:pointer; }
.edit-btn { background-color:#457b9d;color:white; }
.del-btn { background-color:#e63946;color:white; }

.modal {
  display:none; position:fixed; z-index:1000; left:0; top:0; width:100%; height:100%;
  background:rgba(0,0,0,0.5); justify-content:center; align-items:center;
}
.modal-content {
  background:white; padding:20px; border-radius:10px; width:300px; text-align:center;
}
.modal input { width:80%; margin-bottom:10px; padding:8px; border-radius:6px; border:1px solid #ccc; }
</style>

<h3>⚽ Team Management</h3>

<table>
  <tr>
    <th>No.</th>
    <th>Team Name</th>
    <?php if ($isAdmin): ?><th>Actions</th><?php endif; ?>
  </tr>

  <?php foreach ($teams as $t): ?>
  <tr>
    <td><?= $t['team_id'] ?></td>
    <td>
      <a href="../analysis/team_detail.php?team_id=<?= $t['team_id'] ?>" style="color:#1d3557; text-decoration:none;">
        <?= htmlspecialchars($t['team_name']) ?>
      </a>
    </td>

    <?php if ($isAdmin): ?>
    <td>
      <button class="edit-btn"
        onclick="openEditModal(<?= $t['team_id'] ?>, '<?= htmlspecialchars($t['team_name'], ENT_QUOTES) ?>')">
        ✏️ Edit
      </button>

      <button class="del-btn" onclick="openDeleteModal(<?= $t['team_id'] ?>)">🗑 Delete</button>
    </td>
    <?php endif; ?>
  </tr>
  <?php endforeach; ?>
</table>

<!-- 모달 섹션 -->
<?php if ($isAdmin): ?>

<!-- Edit Modal -->
<div id="editModal" class="modal">
  <div class="modal-content">
    <h4>✏️ Edit Team</h4>
    <form method="POST">
      <input type="hidden" name="team_id" id="edit_id">
      <input type="text" name="team_name" id="edit_name" required><br>
      <button type="submit" name="edit_team">Save</button>
      <button type="button" onclick="closeModal('editModal')">Cancel</button>
    </form>
  </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal"class="modal">
  <div class="modal-content">
    <h4>🗑 Delete Team</h4>
    <p>Are you sure you want to delete this team?</p>
    <form method="POST">
      <input type="hidden" name="team_id" id="delete_id">
      <button type="submit" name="delete_team" style="background:#e63946;">Delete</button>
      <button type="button" onclick="closeModal('deleteModal')">Cancel</button>
    </form>
  </div>
</div>

<?php endif; ?>

<script>
function openEditModal(id, name) {
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_name').value = name;
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

