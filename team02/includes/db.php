<?php
// DB CONNECTION 

$host = "localhost";
$dbname = "team02";      // 팀 DB 이름
$user = "team02";        // DB 사용자명
$pass = "team02";        // DB 비밀번호

try {
    // PDO 연결
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // 에러 예외 처리
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 결과 기본 fetch 모드 설정
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("DB 연결 실패: " . $e->getMessage());
}
?>
