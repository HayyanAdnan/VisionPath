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

// Allow both GET and POST for browser convenience
$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'POST' && $method !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

try {
    $pdo = getDBConnection();
    $csvFile = __DIR__ . '/data/esco/occupations.csv';
    
    if (!file_exists($csvFile)) {
        throw new Exception("File not found: $csvFile");
    }
    
    $handle = fopen($csvFile, 'r');
    
    // Read header row
    $header = fgetcsv($handle);
    
    // Find column indexes
    $titleIndex = array_search('preferredLabel', $header);
    if ($titleIndex === false) $titleIndex = 1; // Fallback
    $descIndex = array_search('altLabels', $header);
    if ($descIndex === false) $descIndex = 2; // Fallback
    $iscoIndex = array_search('iscoGroup', $header);
    if ($iscoIndex === false) $iscoIndex = 0; // Fallback
    $uriIndex = array_search('conceptUri', $header);
    if ($uriIndex === false) $uriIndex = 0; // Fallback
    
    $imported = 0;
    $updated = 0;
    
    while (($row = fgetcsv($handle)) !== FALSE) {
        // Get values
        $title = trim($row[$titleIndex] ?? '');
        $description = trim($row[$descIndex] ?? '');
        $iscoCode = trim($row[$iscoIndex] ?? '');
        $uri = trim($row[$uriIndex] ?? '');
        
        if (empty($title)) continue;
        
        // Clean title
        $title = preg_replace('/\s+/', ' ', $title);
        
        // Map ISCO to category
        $category = mapISCOToCategory($iscoCode);
        
        // Check if career already exists
        $stmt = $pdo->prepare("SELECT id FROM careers WHERE title = ?");
        $stmt->execute([$title]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE careers SET 
                description = ?, 
                category = ?,
                updated_at = NOW() 
                WHERE id = ?");
            $stmt->execute([$description, $category, $existing['id']]);
            $updated++;
        } else {
            $stmt = $pdo->prepare("INSERT INTO careers 
                (title, description, category, is_active, match_score, is_emerging) 
                VALUES (?, ?, ?, 1, 50, 0)");
            $stmt->execute([$title, $description, $category]);
            $imported++;
        }
    }
    
    fclose($handle);
    
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
    
    // ISCO-08 codes start with a digit 1-9
    $firstChar = substr($iscoCode, 0, 1);
    return $categories[$firstChar] ?? 'General';
}

function getCareerCount($pdo) {
    $stmt = $pdo->query("SELECT COUNT(*) FROM careers");
    return (int)$stmt->fetchColumn();
}
?>