<?php
// api/check_login.php
require_once '../config/auth.php';

header('Content-Type: application/json');

$response = ['loggedIn' => isLoggedIn()];
if (isLoggedIn()) {
    $user = getCurrentUser();
    $response['user'] = [
        'id' => $user['id'],
        'name' => $user['full_name'],
        'email' => $user['email'],
        'age' => $user['age'] ?? null,
        'city' => $user['city'] ?? null
    ];
}

echo json_encode($response);
?>