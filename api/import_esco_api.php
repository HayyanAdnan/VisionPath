<?php
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

$apiKey = $_GET['key'] ?? '';
$expectedKey = getenv('ADMIN_API_KEY') ?: '';
if ($expectedKey === '' || !hash_equals($expectedKey, $apiKey)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid API key']);
    exit;
}

// Allow GET for browser convenience
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

try {
    $pdo = getDBConnection();
    
    // Fetch from ESCO API
    $url = "https://esco.ec.europa.eu/api/esco/1.2.0/occupation?limit=5000";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200 || !$response) {
        throw new Exception("ESCO API request failed with code: $httpCode");
    }
    
    $data = json_decode($response, true);
    
    if (!isset($data['_embedded']['results'])) {
        throw new Exception("No results found in ESCO API response");
    }
    
    $imported = 0;
    $updated = 0;
    
    foreach ($data['_embedded']['results'] as $occ) {
        $title = $occ['title'] ?? $occ['preferredLabel'] ?? 'Unknown';
        $description = $occ['description'] ?? '';
        $iscoCode = $occ['iscoGroup'] ?? '';
        $category = mapISCOToCategory($iscoCode);
        
        $title = trim(preg_replace('/\s+/', ' ', $title));
        if (empty($title)) continue;
        
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM careers WHERE title = ?");
        $stmt->execute([$title]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE careers SET 
                description = ?, category = ?, updated_at = NOW() 
                WHERE id = ?");
            $stmt->execute([$description, $category, $existing['id']]);
            $updated++;
        } else {
            $stmt = $pdo->prepare("INSERT INTO careers 
                (title, description, category, is_active, match_score) 
                VALUES (?, ?, ?, 1, 50)");
            $stmt->execute([$title, $description, $category]);
            $imported++;
        }
    }
    
    echo json_encode([
        'success' => true,
        'message' => "Imported $imported new careers, updated $updated existing careers",
        'imported' => $imported,
        'updated' => $updated,
        'total' => getCareerCount($pdo)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function mapISCOToCategory($iscoCode) {
    $categories = [
        '1' => 'Management',
        '2' => 'Professional',
        '3' => 'Technical',
        '4' => 'Administrative',
        '5' => 'Service',
        '6' => 'Agriculture',
        '7' => 'Trades',
        '8' => 'Manufacturing',
        '9' => 'Entry Level',
        '0' => 'Government'
    ];
    if (empty($iscoCode)) return 'General';
    $firstChar = substr($iscoCode, 0, 1);
    return $categories[$firstChar] ?? 'General';
}

function getCareerCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM careers");
    return (int)$stmt->fetchColumn();
}
?>