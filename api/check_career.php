<?php
// api/check_career.php
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['hasCareer' => false, 'message' => 'Not logged in']);
    exit;
}

$career = getFinalizedCareer();

echo json_encode([
    'hasCareer' => $career !== null,
    'career' => $career
]);
?>