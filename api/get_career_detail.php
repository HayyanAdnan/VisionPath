<?php
require_once '../config/auth.php';
header('Content-Type: application/json');
global $pdo;

$id=(int)($_GET['career_id'] ?? 0);
if(!$id){ echo json_encode(['success'=>false,'message'=>'career_id required']); exit; }

$stmt=$pdo->prepare("SELECT * FROM careers WHERE id=?");
$stmt->execute([$id]);
$c=$stmt->fetch(PDO::FETCH_ASSOC);

if(!$c){ echo json_encode(['success'=>false,'message'=>'Career not found']); exit; }

$roadmap=[
 'beginner'=>['Learn fundamentals','Complete introductory courses','Build a basic project'],
 'intermediate'=>['Develop portfolio','Learn industry tools','Work on real projects'],
 'advanced'=>['Specialize','Gain certifications','Apply for professional roles']
];

echo json_encode(['success'=>true,'career'=>$c,'roadmap'=>$roadmap]);
