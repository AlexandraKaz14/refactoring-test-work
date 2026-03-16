<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\DadataController;
use App\Http\CurlHttpClient;
use App\Services\Dadata;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

if (empty($_ENV['DADATA_API_KEY'])) {
    throw new \RuntimeException('DADATA_API_KEY is not configured');
}

$dadata = new Dadata($_ENV['DADATA_API_KEY'], new CurlHttpClient());

return new DadataController($dadata);
