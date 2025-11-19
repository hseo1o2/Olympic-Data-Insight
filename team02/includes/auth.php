<?php
// AUTHENTICATION & ACCESS CONTROL 

// 세션 시작 (중복 방지)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 로그인 여부 확인
function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

// 관리자 여부 확인
function isAdmin(): bool {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

// 로그인 강제 (비로그인 시 login.php 이동)
function requireLogin(): void {
    if (!isLoggedIn()) {
        header("Location: /team02/auth/login.php");
        exit;
    }
}

// 관리자만 접근 허용 (일반 사용자는 뒤로가기)
function requireAdmin(): void {
    if (!isAdmin()) {
        echo "<script>alert('관리자만 접근할 수 있습니다.');history.back();</script>";
        exit;
    }
}
?>
