<?php
session_start();

$servername = "localhost";
$username = "dev";
$password = "Phantom0219?";
$database = "auth";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $database);

// 연결 실패 시 오류 처리
if ($conn->connect_error) {
    die("MySQL 연결 실패: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['id'];
    // 입력된 패스워드를 SHA-256으로 해싱
    $password = hash('sha256', $_POST['password']);

    // 로그인 로직 처리
    $sql = "SELECT * FROM member WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // 데이터베이스에 저장된 해시된 비밀번호와 입력된 해시된 비밀번호 비교
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

    header('Content-Type: application/json');
    echo json_encode($response);
}

// 세션 유효 시간 제한 (30분)
if (isset($_SESSION['login_time']) && time() - $_SESSION['login_time'] > 1800) {
    // 세션 만료 시 로그아웃 처리
    session_unset();
    session_destroy();
}

// 리소스 해제
if (isset($stmt)) {
    $stmt->close();
}
$conn->close();
?>
