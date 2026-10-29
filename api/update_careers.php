<?php
// api/update_careers.php
require_once '../config/database.php';

header('Content-Type: application/json');

// Only admin can run this (add your own security)
$admin_key = $_GET['key'] ?? '';
if (ADMIN_API_KEY === '' || !hash_equals(ADMIN_API_KEY, $admin_key)) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Function to fetch careers from external sources
function fetchEmergingCareers() {
    // This is a simplified version - you'd implement actual scraping here
    $sources = [
        'https://www.linkedin.com/emerging-jobs/',
        'https://www.weforum.org/agenda/archive/jobs-of-tomorrow/'
    ];
    
    // For demo, return sample emerging careers
    return [
        [
            'title' => 'AI Ethics Specialist',
            'category' => 'AI',
            'growth_rate' => 65.0,
            'ai_risk' => 'low',
            'description' => 'Ensures AI systems are developed and used ethically'
        ],
        [
            'title' => 'Prompt Engineer',
            'category' => 'AI',
            'growth_rate' => 80.0,
            'ai_risk' => 'low',
            'description' => 'Designs effective prompts for large language models'
        ]
    ];
}

function updateCareerHealth() {
    global $pdo;
    
    // Update health scores based on growth and AI risk
    $pdo->exec("
        UPDATE careers 
        SET health_score = GREATEST(0, LEAST(100,
            CASE 
                WHEN growth_rate > 20 THEN 100
                WHEN growth_rate > 10 THEN 90
                WHEN growth_rate > 0 THEN 75
                WHEN growth_rate > -5 THEN 60
                WHEN growth_rate > -10 THEN 40
                ELSE 20
            END -
            CASE 
                WHEN ai_replacement_risk = 'critical' THEN 40
                WHEN ai_replacement_risk = 'high' THEN 25
                WHEN ai_replacement_risk = 'medium' THEN 10
                ELSE 0
            END
        ))
    ");
}

function autoRemoveDyingCareers() {
    global $pdo;
    
    // Find careers that should be archived
    $stmt = $pdo->prepare("
        SELECT id, title, growth_rate, ai_replacement_risk, health_score 
        FROM careers 
        WHERE status IN ('active', 'declining')
        AND (growth_rate < -15 OR ai_replacement_risk = 'critical' OR health_score < 30)
    ");
    $stmt->execute();
    $dying = $stmt->fetchAll();
    
    $archived = 0;
    foreach ($dying as $career) {
        $stmt = $pdo->prepare("
            UPDATE careers 
            SET status = 'archived', 
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$career['id']]);
        
        // Log the change
        $stmt = $pdo->prepare("
            INSERT INTO career_change_log (career_id, action, old_status, new_status, reason)
            VALUES (?, 'archived', 'active', 'archived', ?)
        ");
        $stmt->execute([
            $career['id'],
            "Auto-archived: Growth {$career['growth_rate']}%, Health {$career['health_score']}"
        ]);
        $archived++;
    }
    
    return $archived;
}

try {
    // 1. Add emerging careers
    $emerging = fetchEmergingCareers();
    $added = 0;
    
    foreach ($emerging as $career) {
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM careers WHERE title = ?");
        $stmt->execute([$career['title']]);
        if (!$stmt->fetch()) {
            $stmt = $pdo->prepare("
                INSERT INTO careers (title, category, growth_rate, ai_replacement_risk, description, status, discovered_date, last_validated)
                VALUES (?, ?, ?, ?, ?, 'emerging', CURDATE(), CURDATE())
            ");
            $stmt->execute([
                $career['title'],
                $career['category'],
                $career['growth_rate'],
                $career['ai_risk'],
                $career['description']
            ]);
            $added++;
        }
    }
    
    // 2. Update health scores
    updateCareerHealth();
    
    // 3. Remove dying careers
    $archived = autoRemoveDyingCareers();
    
    // 4. Clear old caches
    $pdo->exec("DELETE FROM career_matches WHERE expires_at < NOW()");
    
    echo json_encode([
        'success' => true,
        'message' => 'Careers updated successfully',
        'added' => $added,
        'archived' => $archived,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>