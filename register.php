<?php
require_once __DIR__ . '/common.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') respond(['ok'=>false,'error'=>'method_not_allowed'],405);
$in = json_input();
$name = trim((string)($in['name'] ?? ''));
$email = strtolower(trim((string)($in['email'] ?? '')));
$password = (string)($in['password'] ?? '');
if (mb_strlen($name) < 2 || mb_strlen($name) > 100) respond(['ok'=>false,'error'=>'invalid_name'],422);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) respond(['ok'=>false,'error'=>'invalid_email'],422);
if (strlen($password) < 10) respond(['ok'=>false,'error'=>'password_min_10_chars'],422);
try {
    $q = db()->prepare('INSERT INTO users(name,email,password_hash) VALUES(?,?,?)');
    $q->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
    $id = (int)db()->lastInsertId();
    respond(['ok'=>true,'user'=>['id'=>$id,'name'=>$name,'email'=>$email],'token'=>issue_token($id)],201);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') respond(['ok'=>false,'error'=>'email_already_registered'],409);
    respond(['ok'=>false,'error'=>'registration_failed'],500);
}
