<?php

/** @var \App\Http\Controllers\DadataController $controller */
$controller = require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

$path  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$query = trim($input['query'] ?? $_GET['query'] ?? '');

$routes = ['/inn', '/bank', '/country', '/address'];

if (!in_array($path, $routes)) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

if ($query === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter "query" is required']);
    exit;
}

$result = match ($path) {
    '/inn'     => $controller->inn($query),
    '/bank'    => $controller->bank($query),
    '/country' => $controller->country($query),
    '/address' => $controller->address($query, $input['locations'] ?? null),
};

echo json_encode($result);
