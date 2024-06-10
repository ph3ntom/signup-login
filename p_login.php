<?php
// 고정된 아이디와 비밀번호
$fixed_username = "admin";
$fixed_password = "Admin1234?";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['id'];
    $password = $_POST['password'];

    // 입력된 아이디와 비밀번호 확인
    if ($username === $fixed_username && $password === $fixed_password) {
        // 로그인 성공
        $response = array('success' => true);
    } else {
        $response = array('success' => false, 'error' => 'Invalid username or password');
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
