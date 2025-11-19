<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

// GET에서 팀 이름 받기
$teamName = $_GET['team'] ?? null;

if (!$teamName) {
    echo "<p style='text-align:center;color:red;'>Invalid team selected.</p>";
    include '../partials/footer.php';
    exit;
}

// 팀 ID 가져오기
$qTeam = $pdo->prepare("SELECT team_id FROM teams WHERE team_name = :tname");
$qTeam->execute([":tname" => $teamName]);
$teamRow = $qTeam->fetch();

if (!$teamRow) {
    echo "<p style='text-align:center;color:red;'>Team not found.</p>";
    include '../partials/footer.php';
    exit;
}

$tid = $teamRow['team_id'];

// Goal Event 상세 요청
$sql = "
SELECT 
    me.minute,
    p.player_name,
    t2.team_name AS opponent,
    m.match_date,
    me.event_type
FROM match_events me
JOIN players p ON me.player_id = p.player_id
JOIN matches m ON me.match_id = m.match_id
JOIN teams t1 ON me.team_id = t1.team_id
JOIN teams t2 ON 
    (CASE 
        WHEN m.home_team_id = t1.team_id THEN m.away_team_id
        ELSE m.home_team_id
    END) = t2.team_id
WHERE me.team_id = :tid
AND me.event_type LIKE :etype
ORDER BY m.match_date ASC, me.minute ASC;
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ":tid" => $tid,
    ":etype" => "%goal%"
]);

$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
.container {
    width: 70%;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}
table {
    width:100%;
    border-collapse: collapse;
}
th {
    background:#1d3557;
    color:white;
    padding:10px;
}
td {
    padding:10px;
    border-bottom:1px solid #eee;
    text-align:center;
}
h2 {
    text-align:center;
    color:#1d3557;
}
.back a {
    display:block;
    text-align:center;
    margin-top:20px;
    text-decoration:none;
    color:#457b9d;
    font-weight:bold;
}
.back a:hover {
    color:#e63946;
}
</style>

<div class="container">
    <h2>🔍 Goal Details — <?= htmlspecialchars($teamName) ?></h2>

    <table>
        <tr>
            <th>Date</th>
            <th>Minute</th>
            <th>Scorer</th>
            <th>Opponent</th>
            <th>Type</th>
        </tr>

        <?php if (count($events) === 0): ?>
            <tr>
                <td colspan="5">No goal events for this team.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($events as $e): ?>
            <tr>
                <td><?= $e['match_date'] ?></td>
                <td><?= $e['minute'] ?></td>
                <td><?= htmlspecialchars($e['player_name']) ?></td>
                <td><?= htmlspecialchars($e['opponent']) ?></td>
                <td><?= htmlspecialchars($e['event_type']) ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table>

    <div class="back">
        <a href="rollup_goals.php">← Back to Rollup Summary</a>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
