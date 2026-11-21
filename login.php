<?php
require_once __DIR__ . '/common.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok'=>false,'error'=>'method_not_allowed'],405);
$in=json_input();
$email=strtolower(trim((string)($in['email']??'')));
$password=(string)($in['password']??'');
$q=db()->prepare('SELECT id,name,email,password_hash,status FROM users WHERE email=? LIMIT 1');
$q->execute([$email]); $u=$q->fetch();
if (!$u || !password_verify($password,$u['password_hash']) || $u['status']!=='active') respond(['ok'=>false,'error'=>'invalid_credentials'],401);
respond(['ok'=>true,'user'=>['id'=>(int)$u['id'],'name'=>$u['name'],'email'=>$u['email']],'token'=>issue_token((int)$u['id'])]);
