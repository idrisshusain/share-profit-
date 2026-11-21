<?php
require_once __DIR__ . '/common.php';
$u=current_user();
$q=db()->prepare('SELECT id,amount,currency,type,status,source,note,created_at FROM wallet_transactions WHERE user_id=? ORDER BY id DESC LIMIT 100');
$q->execute([$u['id']]);
respond(['ok'=>true,'transactions'=>$q->fetchAll()]);
