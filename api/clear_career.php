<?php
// api/clear_career.php
require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

global $pdo;
$user = getCurrentUser();

$stmt = $pdo->prepare("UPDATE users SET finalized_career = NULL WHERE id = ?");
$result = $stmt->execute([$user['id']]);

if ($result) {
    // Also clear it from the current session
    unset($_SESSION['finalized_career']);

    echo json_encode([
        'success' => true,
        'message' => 'Career cleared successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to clear career'
    ]);
}
?>
