<?php
require_once '../config/auth.php';
header('Content-Type: application/json');
if (!isLoggedIn()) { echo json_encode(['success'=>false,'message'=>'Not logged in']); exit; }

$user=getCurrentUser(); $userId=(int)$user['id'];
$limit=max(1,min(50,(int)($_GET['limit'] ?? 10)));
global $pdo;

try{
 $stmt=$pdo->prepare("SELECT interests,strengths FROM users WHERE id=?");
 $stmt->execute([$userId]);
 $profile=$stmt->fetch(PDO::FETCH_ASSOC);

 $interests=json_decode($profile['interests'] ?? '[]', true) ?: [];
 $strengths=json_decode($profile['strengths'] ?? '[]', true) ?: [];

 $careers=$pdo->query("SELECT * FROM careers WHERE status IS NULL OR status!='archived'")->fetchAll(PDO::FETCH_ASSOC);

 $matches=[];
 foreach($careers as $c){
   $score=50;
   $text=strtolower(json_encode($c));
   foreach($interests as $i){ if(stripos($text,strtolower($i))!==false) $score+=8; }
   foreach($strengths as $s){ if(stripos($text,strtolower($s))!==false) $score+=6; }
   $score+=(float)($c['growth_rate'] ?? 0)/5;
   $score-=(float)($c['ai_replacement_risk'] ?? 0)/10;
   $c['match_score']=round(max(0,min(100,$score)),1);
   $matches[]=$c;
 }
 usort($matches, fn($a,$b)=>$b['match_score']<=>$a['match_score']);
 $matches=array_slice($matches,0,$limit);
 echo json_encode(['success'=>True,'from_cache'=>False,'matches'=>$matches]);
}catch(Throwable $e){
 echo json_encode(['success'=>false,'message'=>$e->getMessage()]);
}
