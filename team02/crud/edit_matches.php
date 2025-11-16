<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

$isAdmin = isAdmin();

// -----------------------------
// Load Match
// -----------------------------
$match_id = $_GET['match_id'] ?? null;
if (!$match_id) {
    echo "<h2 style='text-align:center;color:red;'>Invalid match ID</h2>";
    include '../partials/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.*, 
           t1.team_name AS home_team_name, 
           t2.team_name AS away_team_name,
           r.referee_name
    FROM matches m
    JOIN teams t1 ON m.home_team_id = t1.team_id
    JOIN teams t2 ON m.away_team_id = t2.team_id
    LEFT JOIN referees r ON m.referee_id = r.referee_id
    WHERE match_id = :mid
");
$stmt->execute([':mid' => $match_id]);
$match = $stmt->fetch();

if (!$match) {
    echo "<h2 style='text-align:center;color:red;'>Match not found.</h2>";
    include '../partials/footer.php';
    exit;
}

// Load Players
$players = $pdo->query("SELECT player_id, team_id, player_name FROM players ORDER BY player_name")->fetchAll(PDO::FETCH_ASSOC);

// -----------------------------
// POST: Add Event
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_event']) && $isAdmin) {
    $team = $_POST['team_id'];
    $player = $_POST['player_id'];
    $type = $_POST['event_type'];
    $minute = $_POST['minute'];
    $assist = ($_POST['assist_player_id'] ?? null) ?: null;

    $stmt = $pdo->prepare("
        INSERT INTO match_events (match_id, team_id, player_id, assist_player_id, minute, event_type)
        VALUES (:mid, :tid, :pid, :aid, :min, :etype)
    ");
    $stmt->execute([
        ':mid'=>$match_id, ':tid'=>$team, ':pid'=>$player, ':aid'=>$assist,
        ':min'=>$minute, ':etype'=>$type
    ]);

    updateMatchScore($pdo, $match_id);
    header("Location: edit_matches.php?match_id=$match_id#events"); // ← 수정됨
    exit;
}

// -----------------------------
// POST: Edit Event
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_event']) && $isAdmin) {
    $eid = $_POST['event_id'];
    $team = $_POST['team_id'];
    $player = $_POST['player_id'];
    $type = $_POST['event_type'];
    $minute = $_POST['minute'];
    $assist = ($_POST['assist_player_id'] ?? null) ?: null;

    $stmt = $pdo->prepare("
        UPDATE match_events
        SET team_id=:tid, player_id=:pid, assist_player_id=:aid, minute=:min, event_type=:etype
        WHERE event_id=:eid
    ");
    $stmt->execute([
        ':tid'=>$team, ':pid'=>$player, ':aid'=>$assist,
        ':min'=>$minute, ':etype'=>$type, ':eid'=>$eid
    ]);

    updateMatchScore($pdo, $match_id);
    header("Location: edit_matches.php?match_id=$match_id#events"); // ← 수정됨
    exit;
}

// -----------------------------
// POST: Delete Event
// -----------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_event']) && $isAdmin) {
    $eid = $_POST['event_id'];

    $stmt = $pdo->prepare("DELETE FROM match_events WHERE event_id=:eid");
    $stmt->execute([':eid'=>$eid]);

    updateMatchScore($pdo, $match_id);
    header("Location: edit_matches.php?match_id=$match_id#events"); // ← 수정됨
    exit;
}

// -----------------------------
// Load Events
// -----------------------------
$stmt = $pdo->prepare("
    SELECT me.*, t.team_name, 
           p.player_name,
           ap.player_name AS assist_name
    FROM match_events me
    JOIN teams t ON me.team_id = t.team_id
    JOIN players p ON me.player_id = p.player_id
    LEFT JOIN players ap ON me.assist_player_id = ap.player_id
    WHERE me.match_id = :mid
    ORDER BY me.minute ASC, me.event_id ASC
");
$stmt->execute([':mid'=>$match_id]);
$events = $stmt->fetchAll();

// -----------------------------
// Auto Score Update
// -----------------------------
function updateMatchScore($pdo, $match_id) {
    $stmt = $pdo->prepare("SELECT team_id, event_type FROM match_events WHERE match_id=:mid");
    $stmt->execute([':mid'=>$match_id]);
    $rows = $stmt->fetchAll();

    $homeGoals = 0;
    $awayGoals = 0;

    $teamQ = $pdo->prepare("SELECT home_team_id, away_team_id FROM matches WHERE match_id=:mid");
    $teamQ->execute([':mid'=>$match_id]);
    $t = $teamQ->fetch();

    foreach ($rows as $r) {
        if ($r['event_type'] === 'goal' || $r['event_type'] === 'penalty_goal') {
            if ($r['team_id'] == $t['home_team_id']) $homeGoals++;
            if ($r['team_id'] == $t['away_team_id']) $awayGoals++;
        }
    }

    $upd = $pdo->prepare("UPDATE matches SET home_goals=:hg, away_goals=:ag WHERE match_id=:mid");
    $upd->execute([
        ':hg'=>$homeGoals, ':ag'=>$awayGoals, ':mid'=>$match_id
    ]);
}
?>

<style>
.container {
    max-width: 900px;
    margin: 40px auto;
}

.card-box {
    background: #ffffff;
    padding: 25px 28px;
    border-radius: 14px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.07);
    margin-bottom: 35px;
}

h2 {
    font-weight: 700;
    margin-bottom: 5px;
}

h3 {
    font-weight: 700;
    margin-bottom: 18px;
}

label {
    font-weight: 600;
    margin-bottom: 4px;
    display: block;
}

select, input[type="number"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 15px;
}

button {
    border: none;
    border-radius: 6px;
    padding: 9px 14px;
    cursor: pointer;
    font-weight: 600;
}

button.edit {
    background: #2a9d8f;
    color: #fff;
}

button.delete {
    background: #e63946;
    color: #fff;
}

table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
    margin-top: 10px;
}

th {
    background: #457b9d;
    padding: 10px;
    color: #fff;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

tr:hover td {
    background: #f8f9fa;
}

.modal {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
}

.modal-box {
    width: 400px;
    background: #fff;
    padding: 25px;
    border-radius: 14px;
}
</style>

<div class="container">

<h2 style="text-align:center;">
⚽ Match Events — <?= htmlspecialchars($match['home_team_name']) ?> vs <?= htmlspecialchars($match['away_team_name']) ?>
</h2>
<p style="text-align:center; color:#555;">
    Date: <?= $match['match_date'] ?> · Referee: <?= $match['referee_name'] ?>
</p>


<!-- ADD EVENT -->
<div class="card-box">
    <h3>Add Event</h3>

<?php if ($isAdmin): ?>
<form method="POST">

    <label>Team</label>
    <select name="team_id" id="team_select" required>
        <option value="">Select</option>
        <option value="<?= $match['home_team_id'] ?>"><?= $match['home_team_name'] ?></option>
        <option value="<?= $match['away_team_id'] ?>"><?= $match['away_team_name'] ?></option>
    </select>

    <label>Player</label>
    <select name="player_id" id="player_select" required disabled>
        <option value="">Select team first</option>
    </select>

    <label>Event Type</label>
    <select name="event_type" id="type_select" required>
        <option value="goal">Goal</option>
        <option value="penalty_goal">Penalty Goal</option>
        <option value="substitute_in">Substitute In</option>
        <option value="yellow_card">Yellow Card</option>
        <option value="red_card">Red Card</option>
    </select>

    <div id="assist_wrapper" style="display:none;">
        <label>Assist</label>
        <select name="assist_player_id" id="assist_select">
            <option value="">None</option>
        </select>
    </div>

    <label>Minute</label>
    <input type="number" name="minute" min="0" max="120" required>

    <button type="submit" name="add_event" class="edit">Add Event</button>
</form>
<?php endif; ?>
</div>


<!-- EVENT LIST -->
<div class="card-box">
    <h3>Event List</h3>

    <table>
        <tr>
            <th>Min</th>
            <th>Team</th>
            <th>Player</th>
            <th>Assist</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>

<?php foreach ($events as $e): ?>
        <tr>
            <td><?= $e['minute'] ?></td>
            <td><?= htmlspecialchars($e['team_name']) ?></td>
            <td><?= htmlspecialchars($e['player_name']) ?></td>
            <td><?= htmlspecialchars($e['assist_name'] ?? '-') ?></td>
            <td><?= htmlspecialchars($e['event_type']) ?></td>
            <td>
                <button class="edit"
                    onclick="openEditModal(
                        <?= $e['event_id'] ?>,
                        <?= $e['team_id'] ?>,
                        <?= $e['player_id'] ?>,
                        '<?= $e['event_type'] ?>',
                        <?= $e['minute'] ?>,
                        '<?= $e['assist_player_id'] ?>'
                    )"
                >Edit</button>

                <form method="POST" style="display:inline;">
                    <input type="hidden" name="event_id" value="<?= $e['event_id'] ?>">
                    <button class="delete" name="delete_event">Delete</button>
                </form>
            </td>
        </tr>
<?php endforeach; ?>

    </table>
</div>

</div>


<!-- MODAL -->
<div id="editModal" class="modal">
    <div class="modal-box">
        <h3>Edit Event</h3>

        <form method="POST">
            <input type="hidden" name="event_id" id="edit_event_id">

            <label>Team</label>
            <select id="edit_team_select" name="team_id" required>
                <option value="<?= $match['home_team_id'] ?>"><?= $match['home_team_name'] ?></option>
                <option value="<?= $match['away_team_id'] ?>"><?= $match['away_team_name'] ?></option>
            </select>

            <label>Player</label>
            <select id="edit_player_select" name="player_id" required></select>

            <label>Event Type</label>
            <select id="edit_type_select" name="event_type" required>
                <option value="goal">Goal</option>
                <option value="penalty_goal">Penalty Goal</option>
                <option value="substitute_in">Substitute In</option>
                <option value="yellow_card">Yellow Card</option>
                <option value="red_card">Red Card</option>
            </select>

            <div id="edit_assist_wrapper" style="display:none;">
                <label>Assist</label>
                <select id="edit_assist_selector" name="assist_player_id">
                    <option value="">None</option>
                </select>
            </div>

            <label>Minute</label>
            <input type="number" id="edit_minute" name="minute" min="0" max="120" required>

            <button class="edit" name="edit_event">Save</button>
            <button type="button" onclick="closeEditModal()">Cancel</button>
        </form>
    </div>
</div>


<script>
const players = <?= json_encode($players) ?>;

document.getElementById("team_select").addEventListener("change", function() {
    const tid = this.value;
    const pSel = document.getElementById("player_select");
    const aSel = document.getElementById("assist_select");

    pSel.innerHTML = "";
    aSel.innerHTML = "<option value=''>None</option>";

    if (!tid) {
        pSel.disabled = true;
        return;
    }

    const teamPlayers = players.filter(p => p.team_id == tid);
    pSel.disabled = false;

    teamPlayers.forEach(p => {
        pSel.innerHTML += `<option value="${p.player_id}">${p.player_name}</option>`;
    });

    pSel.addEventListener("change", function() {
        const scorer = this.value;

        aSel.innerHTML = "<option value=''>None</option>";
        teamPlayers.forEach(p => {
            if (p.player_id != scorer) {
                aSel.innerHTML += `<option value="${p.player_id}">${p.player_name}</option>`;
            }
        });
    });
});

function updateAssistVisibility() {
    const type = document.getElementById("type_select").value;
    document.getElementById("assist_wrapper").style.display =
        (type === "goal" || type === "penalty_goal") ? "block" : "none";
}
document.getElementById("type_select").addEventListener("change", updateAssistVisibility);
updateAssistVisibility();


function openEditModal(eid, teamId, playerId, type, minute, assistId) {
    document.getElementById("editModal").style.display = "flex";

    document.getElementById("edit_event_id").value = eid;
    document.getElementById("edit_minute").value = minute;

    const tSel = document.getElementById("edit_team_select");
    const pSel = document.getElementById("edit_player_select");
    const aSel = document.getElementById("edit_assist_selector");
    const typeSel = document.getElementById("edit_type_select");

    tSel.value = teamId;
    typeSel.value = type;

    const teamPlayers = players.filter(p => p.team_id == teamId);

    pSel.innerHTML = "";
    aSel.innerHTML = "<option value=''>None</option>";

    teamPlayers.forEach(p => {
        pSel.innerHTML += `<option value="${p.player_id}">${p.player_name}</option>`;
        if (p.player_id != playerId) {
            aSel.innerHTML += `<option value="${p.player_id}">${p.player_name}</option>`;
        }
    });

    pSel.value = playerId;
    if (assistId) aSel.value = assistId;

    document.getElementById("edit_assist_wrapper").style.display =
        (type === "goal" || type === "penalty_goal") ? "block" : "none";

    typeSel.addEventListener("change", function() {
        document.getElementById("edit_assist_wrapper").style.display =
            (this.value === "goal" || this.value === "penalty_goal") ? "block" : "none";
    });
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
</script>

<?php include '../partials/footer.php'; ?>
