<?php
// api/clear_cache.php
require_once '../config/auth.php';

header('Content-Type: application/json');

// Admin check - change to your admin email
$admin_email = 'admin@visionpath.pk';
if (!isLoggedIn() || $_SESSION['user']['email'] !== $admin_email) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$admin_key = $_GET['key'] ?? '';
if (ADMIN_API_KEY === '' || !hash_equals(ADMIN_API_KEY, $admin_key)) {
    echo json_encode(['success' => false, 'message' => 'Invalid admin key']);
    exit;
}

try {
    global $pdo;
    
    // Clear expired matches
    $pdo->exec("DELETE FROM career_matches WHERE expires_at < NOW()");
    
    // Clear all matches for non-active users (optional)
    // $pdo->exec("DELETE FROM career_matches");
    
    echo json_encode([
        'success' => true,
        'message' => 'Cache cleared successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>