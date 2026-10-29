<?php
// config/auth.php - Ensure this works with InfinityFree

if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}

require_once __DIR__ . '/database.php';

// ... rest of your auth functions remain the same ...
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// config/auth.php
require_once __DIR__ . '/database.php';

function registerUser($full_name, $email, $password, $age = null, $city = null) {
    global $pdo;
    
    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, age, city) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute([$full_name, $email, $hashed_password, $age, $city]);
    
    if ($result) {
        return ['success' => true, 'user_id' => $pdo->lastInsertId()];
    }
    return ['success' => false, 'message' => 'Registration failed'];
}

function loginUser($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        // Remove password from session data
        unset($user['password']);
        $_SESSION['user'] = $user;
        
        // Update user progress if exists
        if ($user['user_progress']) {
            $_SESSION['user_progress'] = json_decode($user['user_progress'], true);
        }
        if ($user['finalized_career']) {
            $_SESSION['finalized_career'] = json_decode($user['finalized_career'], true);
        }
        
        return ['success' => true, 'user' => $user];
    }
    return ['success' => false, 'message' => 'Invalid email or password'];
}

function isLoggedIn() {
    return isset($_SESSION['user']);
}

function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

function updateUserData($data) {
    global $pdo;
    $user = getCurrentUser();
    if (!$user) return false;
    
    $updates = [];
    $params = [];
    
    foreach ($data as $key => $value) {
        if (in_array($key, ['full_name', 'age', 'city', 'education', 'career_goal', 'life_goal', 'work_style'])) {
            $updates[] = "$key = ?";
            $params[] = $value;
        }
    }
    
    // Handle JSON fields
    if (isset($data['interests'])) {
        $updates[] = "interests = ?";
        $params[] = json_encode($data['interests']);
    }
    if (isset($data['strengths'])) {
        $updates[] = "strengths = ?";
        $params[] = json_encode($data['strengths']);
    }
    if (isset($data['subjects'])) {
        $updates[] = "subjects = ?";
        $params[] = json_encode($data['subjects']);
    }
    if (isset($data['finalized_career'])) {
        $updates[] = "finalized_career = ?";
        $params[] = json_encode($data['finalized_career']);
        $_SESSION['finalized_career'] = $data['finalized_career'];
    }
    if (isset($data['user_progress'])) {
        $updates[] = "user_progress = ?";
        $params[] = json_encode($data['user_progress']);
        $_SESSION['user_progress'] = $data['user_progress'];
    }
    
    if (empty($updates)) return false;
    
    $params[] = $user['id'];
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($params);
    
    if ($result) {
        // Refresh session data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$user['id']]);
        $updatedUser = $stmt->fetch();
        if ($updatedUser) {
            unset($updatedUser['password']);
            $_SESSION['user'] = $updatedUser;
            if ($updatedUser['user_progress']) {
                $_SESSION['user_progress'] = json_decode($updatedUser['user_progress'], true);
            }
            if ($updatedUser['finalized_career']) {
                $_SESSION['finalized_career'] = json_decode($updatedUser['finalized_career'], true);
            }
        }
        return true;
    }
    return false;
}

function getUserProgress() {
    return $_SESSION['user_progress'] ?? ['completedMilestones' => []];
}

function getFinalizedCareer() {
    if (isset($_SESSION['finalized_career']) && $_SESSION['finalized_career'] !== null) {
        return $_SESSION['finalized_career'];
    }

    // FIX: session can be stale (e.g. right after sync_career.php ran, or a
    // fresh session that hasn't re-logged-in since finalizing). Fall back
    // to the database, which is always the source of truth.
    global $pdo;
    $user = getCurrentUser();
    if (!$user) return null;

    $stmt = $pdo->prepare("SELECT finalized_career FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $row = $stmt->fetch();

    if ($row && $row['finalized_career']) {
        $career = json_decode($row['finalized_career'], true);
        $_SESSION['finalized_career'] = $career; // repopulate session
        return $career;
    }

    return null;
}

function isCareerFinalized() {
    return getFinalizedCareer() !== null;
}
// Add to config/auth.php

function syncCareerToDatabase($career) {
    global $pdo;
    $user = getCurrentUser();
    if (!$user) return false;

    $stmt = $pdo->prepare("UPDATE users SET finalized_career = ? WHERE id = ?");
    $result = $stmt->execute([json_encode($career), $user['id']]);

    // FIX: previously this never updated $_SESSION, so the dashboard could
    // show stale/no career data for the rest of the session after finalizing.
    if ($result) {
        $_SESSION['finalized_career'] = $career;
    }

    return $result;
}

function getStudentInterests() {
    $user = getCurrentUser();
    if (!$user) return [];
    return json_decode($user['interests'] ?? '[]', true);
}

function updateStudentProfile($data) {
    return updateUserData($data);
}

function getCachedMatches($student_id, $limit = 10) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT cm.*, c.title, c.category, c.growth_rate, c.ai_replacement_risk 
        FROM career_matches cm
        JOIN careers c ON cm.career_id = c.id
        WHERE cm.student_id = ? AND cm.expires_at > NOW()
        ORDER BY cm.match_score DESC
        LIMIT ?
    ");
    $stmt->execute([$student_id, $limit]);
    return $stmt->fetchAll();
}
?>
