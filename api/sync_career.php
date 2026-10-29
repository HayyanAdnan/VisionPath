<?php
// api/sync_career.php
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$career = $input['career'] ?? null;
$progress = $input['progress'] ?? null;

if (!$career) {
    echo json_encode(['success' => false, 'message' => 'No career data provided']);
    exit;
}

// Save to database
$updateData = ['finalized_career' => $career];
if ($progress) {
    $updateData['user_progress'] = $progress;
}

$result = updateUserData($updateData);

if ($result) {
    // Also update the session
    $_SESSION['finalized_career'] = $career;
    
    echo json_encode([
        'success' => true,
        'message' => 'Career synced successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to sync career'
    ]);
}
?>