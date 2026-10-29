<?php
// api/save_profile.php
//
// THE MISSING LINK: the assessment in Roadmap.html collects the
// student's interests / strengths / education / goals, but was only
// ever saving them to localStorage. get_matches.php (and the
// find_matching_careers stored procedure) read this data from the
// `users` table, so without this endpoint the matching step always
// ran on stale or empty data.
//
// Roadmap.html now calls this endpoint right before requesting matches.

require_once '../config/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'No profile data provided']);
    exit;
}

// Only accept known, expected fields (updateUserData already whitelists
// scalar fields; we pass through everything relevant from the assessment)
$allowed = [
    'age', 'city', 'education', 'career_goal', 'life_goal', 'work_style',
    'interests', 'strengths', 'subjects'
];

$data = [];
foreach ($allowed as $key) {
    if (isset($input[$key])) {
        $data[$key] = $input[$key];
    }
}

// Frontend sends camelCase (careerGoal, lifeGoal, workStyle) - normalise
if (isset($input['careerGoal'])) $data['career_goal'] = $input['careerGoal'];
if (isset($input['lifeGoal'])) $data['life_goal'] = $input['lifeGoal'];
if (isset($input['workStyle'])) $data['work_style'] = $input['workStyle'];

if (empty($data)) {
    echo json_encode(['success' => false, 'message' => 'No valid fields to save']);
    exit;
}

$result = updateUserData($data);

if ($result) {
    echo json_encode([
        'success' => true,
        'message' => 'Profile saved successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to save profile'
    ]);
}
?>
