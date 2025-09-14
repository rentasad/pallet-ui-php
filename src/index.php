<?php
declare(strict_types=1);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($path === '/' || $path === '') {
    require __DIR__ . '/views/home.php';
    exit;
}

if ($path === '/scan' && $method === 'GET') {
    require __DIR__ . '/views/scan_form.php';
    exit;
}

if ($path === '/scan' && $method === 'POST') {
    require __DIR__ . '/scan_submit.php';
    exit;
}

if ($path === '/list' && $method === 'GET') {
    require __DIR__ . '/list.php';
    exit;
}

if ($path === '/query' && $method === 'GET') {
    require __DIR__ . '/views/query_form.php';
    exit;
}

if ($path === '/query' && $method === 'POST') {
    require __DIR__ . '/query.php';
    exit;
}

if ($path === '/healthz') {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'time' => date('c')]);
    exit;
}

http_response_code(404);
echo "<h1>404</h1><p>Route nicht gefunden.</p>";