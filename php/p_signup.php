<?php
header('Content-Type: application/json');

// 정규표현식을 사용한 유효성 검사
$idPattern = '/^[a-zA-Z0-9]{5,}$/';
$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
$namePattern = '/^[가-힣]{2,}$/';
$phonePattern = '/^\d{3}-\d{3,4}-\d{4}$/';
$emailPattern = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';

$id = $_POST['id'];
$password = $_POST['password'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$email = $_POST['email'];

if (!preg_match($idPattern, $id)) {
    $errorMessage = '아이디는 5자 이상의 영문자와 숫자로 구성되어야 합니다.';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

if (!preg_match($passwordPattern, $password)) {
    $errorMessage = '비밀번호는 8자 이상, 대소문자, 숫자, 특수문자를 포함해야 합니다.';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

if (!preg_match($namePattern, $name)) {
    $errorMessage = '이름은 2자 이상의 한글로 구성되어야 합니다.';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

if (!preg_match($phonePattern, $phone)) {
    $errorMessage = '전화번호 형식이 올바르지 않습니다. 예: 010-1234-5678';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

if (!preg_match($emailPattern, $email)) {
    $errorMessage = '이메일 형식이 올바르지 않습니다.';
    echo json_encode(['success' => false, 'message' => $errorMessage]);
    exit;
}

// 회원 가입 성공
echo json_encode(['success' => true]);
?>
