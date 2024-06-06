<?php
session_start(); // 세션 시작

// MySQL 연결 정보
$servername = "localhost";
$username = "dev";
$password = "Phantom0219?";
$database = "auth";

// MySQL 연결 생성
$conn = new mysqli($servername, $username, $password, $database);

// MySQL 연결 확인
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['id'];
    $password = $_POST['password'];

    // 로그인 로직 처리
    $sql = "SELECT * FROM member WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['password'] === $password) {
            // 로그인 성공 시 세션 활성화
            $_SESSION['user_id'] = $row['id']; // 사용자 ID를 세션에 저장
            $_SESSION['login_time'] = time(); // 로그인 시간을 세션에 저장

            $response = array('success' => true);
        } else {
            $response = array('success' => false, 'error' => 'Invalid username or password');
        }
    } else {
        $response = array('success' => false, 'error' => 'Invalid username or password');
    }

    // JSON 형식으로 응답 보내기
    header('Content-Type: application/json');
    echo json_encode($response);
}

// 세션 유효 시간 제한 (2시간)
if (isset($_SESSION['login_time']) && time() - $_SESSION['login_time'] > 7200) { // 2시간 = 7200초
    // 세션 만료 시 로그아웃 처리
    session_unset();
    session_destroy();
}

// MySQL 연결 닫기
$stmt->close();
$conn->close();
?>
