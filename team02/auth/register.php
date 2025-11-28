<?php
/**
 * Backend: 김현영
 * Frontend: 강민경
 */
session_start();
require_once '../includes/db.php';
require_once '../includes/auth.php';

// 이미 로그인된 경우 바로 메인으로
if (isLoggedIn()) {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm  = trim($_POST['confirm'] ?? '');

    // 입력값 검증
    if ($username === '' || $password === '' || $confirm === '') {
        $error = "⚠ Please fill in all fields.";
    } elseif ($password !== $confirm) {
        $error = "⚠ Passwords do not match.";
    } else {
        // 사용자 중복 확인
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            $error = "⚠ Username already exists.";
        } else {
            // 비밀번호 해시 적용
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // 기본 역할: user
            $stmt = $pdo->prepare("
                INSERT INTO users (username, password, role)
                VALUES (:u, :p, 'viewer')
            ");
            $stmt->execute([':u' => $username, ':p' => $hash]);

            $success = "Registration successful! You can now <a href='login.php'>login</a>.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Football Performance Insight</title>
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
        .success { color: green; margin-top: 10px; }
        a { color: #1d3557; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <h2>⚽ Football Performance Insight - Register</h2>
    <p>Create your account to explore EPL analytics</p>

    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" placeholder="Choose a username" required><br>

        <label>Password:</label><br>
        <input type="password" name="password" placeholder="Enter password" required><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm" placeholder="Re-enter password" required><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>


