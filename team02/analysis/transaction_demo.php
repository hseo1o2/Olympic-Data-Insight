<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';
requireLogin();
include '../partials/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matchId = $_POST['match_id'] ?? null;

    try {
        $pdo->beginTransaction();

        $pdo->prepare("DELETE FROM match_events WHERE match_id=?")->execute([$matchId]);
        $pdo->prepare("DELETE FROM matches WHERE match_id=?")->execute([$matchId]);

        $pdo->commit();
        $msg = "Match & related events deleted successfully!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $msg = "❌ Error occurred, rollback executed";
    }
}
?>
<h3 style='text-align:center;color:#1d3557;'>🛠 Transaction Demo: Delete Match + Events</h3>

<form method="POST" style="text-align:center;">
    <input type="number" name="match_id" placeholder="Enter Match ID" required>
    <button type="submit">Delete Transaction</button>
</form>

<?php if(isset($msg)): ?>
<p style="text-align:center; font-weight:bold;"><?= $msg ?></p>
<?php endif; ?>

<p style='text-align:center;color:#6c757d;'>* Uses BEGIN, COMMIT, ROLLBACK</p>

<?php include '../partials/footer.php'; ?>
