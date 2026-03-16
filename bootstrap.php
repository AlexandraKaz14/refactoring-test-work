<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\DadataController;
use App\Http\CurlHttpClient;
use App\Services\Dadata;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$dadata = new Dadata($_ENV['DADATA_API_KEY'] ?? '', new CurlHttpClient());

return new DadataController($dadata);
