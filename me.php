<?php
require_once __DIR__ . '/common.php';
$u=current_user();
$q=db()->prepare("SELECT COALESCE(SUM(CASE WHEN type='credit' AND status='confirmed' THEN amount WHEN type='debit' AND status='confirmed' THEN -amount ELSE 0 END),0) AS balance FROM wallet_transactions WHERE user_id=?");
$q->execute([$u['id']]); $balance=$q->fetchColumn();
respond(['ok'=>true,'user'=>$u,'balance'=>(string)$balance,'currency'=>'USD']);
