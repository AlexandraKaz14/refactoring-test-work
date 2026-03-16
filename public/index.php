<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Services\Dadata;
use App\DadataController;
use App\Http\CurlHttpClient;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

header('Content-Type: application/json');

$apiKey = $_ENV['DADATA_API_KEY'] ?? '';
$controller = new DadataController(new Dadata($apiKey, new CurlHttpClient()));

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
