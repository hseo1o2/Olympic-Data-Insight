<?php
/**
 * Backend: 김현영
 * Frontend: 강민경
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 이미 로그인된 경우 index로 리다이렉트
if (isLoggedIn()) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // 사용자 조회
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $db_pass = $user['password'];

        // 비밀번호 검증 (관리자 권한 접속 때문에 평문도 허용)
        if (password_verify($password, $db_pass) || $db_pass === $password) {
            $_SESSION['user'] = [
                'id' => $user['user_id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            // 로그인 후 index.php로 이동
            header("Location: ../index.php");
            exit;
        } else {
            $error = "⚠ Invalid username or password.";
        }
    } else {
        $error = "⚠ No user found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | Football Performance Insight</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        color: #333;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    h2 { color: #1d3557; }
    form {
        background: white;
        padding: 25px 40px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    input {
        width: 240px;
        padding: 8px;
        margin-top: 6px;
        margin-bottom: 14px;
    }
    button {
        background: #1d3557;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover { background: #457b9d; }
    .error { color: red; margin-top: 10px; }
    a { color: #1d3557; text-decoration: none; }
    a:hover { text-decoration: underline; }
</style>
</head>
<body>
<h2>⚽ Football Performance Insight - Login</h2>
<p>Analyze and manage EPL 24–25 season data</p>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" placeholder="Enter username" required><br>

    <label>Password:</label><br>
    <input type="password" name="password" placeholder="Enter password" required><br>

    <button type="submit">Sign In</button>
</form>

<p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>
