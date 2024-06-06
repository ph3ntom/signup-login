<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "dev";
$password = "Phantom0219?";
$dbname = "auth";

// 데이터베이스 연결 생성
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    $errorMessage = "Connection failed: " . $conn->connect_error;
    error_log($errorMessage); 
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

// POST로 받은 데이터
$id = $_POST['id'];

// 아이디 중복 검사 쿼리 준비
$stmt = $conn->prepare("SELECT COUNT(*) FROM member WHERE LOWER(username) = LOWER(?)");
$stmt->bind_param("s", $id);

// 쿼리 실행
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();

// 중복 검사 결과 반환
if ($count > 0) {
    echo json_encode(['isAvailable' => false]);
} else {
    echo json_encode(['isAvailable' => true]);
}

// 리소스 정리
$stmt->close();
$conn->close();
?>
