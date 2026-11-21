<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

function json_input(): array {
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}
function respond(array $data, int $status = 200): never {
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function bearer_token(): ?string {
    $h = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    return preg_match('/Bearer\s+(.+)/i', $h, $m) ? trim($m[1]) : null;
}
function current_user(): array {
    $token = bearer_token();
    if (!$token) respond(['ok'=>false,'error'=>'unauthorized'], 401);
    $hash = hash('sha256', $token);
    $q = db()->prepare('SELECT u.id,u.name,u.email,u.status FROM api_tokens t JOIN users u ON u.id=t.user_id WHERE t.token_hash=? AND t.expires_at>NOW() LIMIT 1');
    $q->execute([$hash]);
    $user = $q->fetch();
    if (!$user || $user['status'] !== 'active') respond(['ok'=>false,'error'=>'unauthorized'], 401);
    return $user;
}
function issue_token(int $userId): string {
    $token = bin2hex(random_bytes(32));
    $q = db()->prepare('INSERT INTO api_tokens(user_id,token_hash,expires_at) VALUES(?,?,DATE_ADD(NOW(), INTERVAL 30 DAY))');
    $q->execute([$userId, hash('sha256', $token)]);
    return $token;
}
