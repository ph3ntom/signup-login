<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "dev";
$password = "Phantom0219?";
$dbname = "auth";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    $errorMessage = "Connection failed: " . $conn->connect_error;
    error_log($errorMessage); 
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

$id = $_POST['id'];
$password = $_POST['password'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

// 정규표현식을 사용한 유효성 검사
$idPattern = '/^[a-zA-Z0-9]{5,}$/';
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
$namePattern = '/^[가-힣]{2,}$/';
$phonePattern = '/^\d{3}-\d{3,4}-\d{4}$/';
$emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

if (!preg_match($idPattern, $id)) {
    $errorMessage = '아이디는 5자 이상의 영문자와 숫자로 구성되어야 합니다.';
    error_log($errorMessage); // 오류 메시지를 로그 파일에 기록
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}
if (!preg_match($passwordPattern, $password)) {
    $errorMessage = '비밀번호는 8자 이상, 대소문자, 숫자, 특수문자를 포함해야 합니다.';
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}
if (!preg_match($namePattern, $name)) {
    $errorMessage = '이름은 2자 이상의 한글로 구성되어야 합니다.';
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}
if (!preg_match($phonePattern, $phone)) {
    $errorMessage = '전화번호 형식이 올바르지 않습니다. 예: 010-1234-5678';
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}
if (!preg_match($emailPattern, $email)) {
    $errorMessage = '이메일 형식이 올바르지 않습니다.';
    error_log($errorMessage);
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

$sql = "INSERT INTO member (username, password, name, phone, email)
VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $id, $password, $name, $phone, $email);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    $errorMessage = '회원 가입에 실패했습니다.';
    error_log($errorMessage); // 오류 메시지를 로그 파일에 기록
    echo json_encode(['success' => false, 'message' => $errorMessage]);
}

$stmt->close();
$conn->close();
?>