<?php

// ============================================
// RAILWAY TEST PACK v1
// ============================================
// Este archivo nos permite probar si Railway:
// - Sirve la carpeta "public"
// - Responde correctamente a HEAD /
// - No está devolviendo 500 por Laravel
// ============================================

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1) Test de HEAD para healthcheck
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'HEAD') {
    http_response_code(200);
    exit;
}

// 2) Respuesta GET simple
http_response_code(200);

header('Content-Type: text/plain');

echo "RAILWAY TEST OK\n";
echo "Server time: " . date('Y-m-d H:i:s') . "\n";
echo "PHP version: " . phpversion() . "\n";
echo "Document root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'N/A') . "\n";
echo "Index path: " . __FILE__ . "\n";
