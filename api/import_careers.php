<?php
// api/import_careers.php
require_once '../config/database.php';

header('Content-Type: application/json');

$admin_key = $_GET['key'] ?? '';
if (ADMIN_API_KEY === '' || !hash_equals(ADMIN_API_KEY, $admin_key)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Sample career data - you'd load from a CSV file
$careers = [
    [
        'title' => 'Software Engineer',
        'category' => 'Technology',
        'growth_rate' => 22.5,
        'ai_risk' => 'low',
        'salary_range_low' => 70000,
        'salary_range_high' => 130000
    ],
    // ... add 13,000+ careers here from your data source
];

$inserted = 0;
foreach ($careers as $career) {
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO careers 
        (title, category, growth_rate, ai_replacement_risk, salary_range_low, salary_range_high, status, last_validated)
        VALUES (?, ?, ?, ?, ?, ?, 'active', CURDATE())
    ");
    $stmt->execute([
        $career['title'],
        $career['category'],
        $career['growth_rate'],
        $career['ai_risk'],
        $career['salary_range_low'],
        $career['salary_range_high']
    ]);
    $inserted += $stmt->rowCount();
}

echo json_encode([
    'success' => true,
    'message' => "Imported $inserted careers"
]);
?>