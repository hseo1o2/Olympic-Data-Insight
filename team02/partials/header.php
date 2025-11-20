<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team02 - Football Performance Insight</title>

    <!-- Chart.js (전역 1회 로드) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Chart Helper -->
    <script>
        function $(selector) { return document.querySelector(selector); }

        function mkChart(sel, type, labels, datasets, opt = {}) {
            const el = $(sel);
            if (!el) return;

            const ctx = el.getContext("2d");
            return new Chart(ctx, {
                type,
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    ...opt
                }
            });
        }
    </script>

    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color:#f9fafb; }
        header { padding: 10px; border-bottom: 2px solid #1d3557; margin-bottom: 20px; }
        nav a { margin-right: 15px; text-decoration:none; color:#1d3557; font-weight:bold; }
        nav a:hover { color:#457b9d; }
        .admin-tag { color:#e63946; font-weight:bold; margin-left:8px; }
    </style>
</head>

<body>
<header>
    <h2>⚽ Football Performance Insight 
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <span class="admin-tag">(Admin Mode)</span>
        <?php endif; ?>
    </h2>

   <nav>
        <a href="/team02/index.php">Dashboard</a> |
        <a href="/team02/analysis/ranking_teams.php">Team Ranking</a> |
        <a href="/team02/analysis/ranking_players.php">Player Ranking</a> |
        <a href="/team02/crud/teams.php">Teams</a> |
        <a href="/team02/crud/players.php">Players</a> |
        <a href="/team02/crud/matches.php">Matches</a> |
        <a href="/team02/analysis/rollup_goals.php">Rollup + Drill Down</a> |
        <a href="/team02/analysis/window_moving_avg.php">Moving Avg</a> |
        <a href="/team02/auth/logout.php" style="color:red;">Logout</a>
    </nav>
</header>


