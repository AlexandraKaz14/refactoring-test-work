<?php

namespace App\Contracts;

interface HttpClientInterface
{
    public function post(string $url, string $body, array $headers): string;
}
