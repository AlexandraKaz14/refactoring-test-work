<?php

/** @var \App\DadataController $controller */
$controller = require_once __DIR__ . '/../bootstrap.php';

header('Content-Type: application/json');

$path  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$input = json_decode(file_get_contents('php://input'), true) ?? [];
$query = $input['query'] ?? $_GET['query'] ?? '';

$result = match ($path) {
    '/inn'     => $controller->inn($query),
    '/bank'    => $controller->bank($query),
    '/country' => $controller->country($query),
    '/address' => $controller->address($query, $input['locations'] ?? null),
    default    => null,
};

if ($result === null) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
} else {
    echo json_encode($result);
}
