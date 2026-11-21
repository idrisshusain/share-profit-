<?php
require_once __DIR__ . '/common.php';
try {
    db()->query('SELECT 1');
    respond(['ok'=>true,'service'=>'Share Profit API','database'=>'connected']);
} catch (Throwable $e) {
    respond(['ok'=>false,'error'=>'service_unavailable'], 503);
}
