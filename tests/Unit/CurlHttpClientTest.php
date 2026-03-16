<?php

namespace Tests\Unit;

use App\Exceptions\HttpException;
use App\Http\CurlHttpClient;
use PHPUnit\Framework\TestCase;

class CurlHttpClientTest extends TestCase
{
    public function testThrowsExceptionOnNonSuccessStatusCode(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessageMatches('/401/');

        $client = new CurlHttpClient();
        $client->post(
            'https://httpbin.org/status/401',
            json_encode([]),
            ['Content-Type: application/json']
        );
    }

    public function testThrowsExceptionOnConnectionFailure(): void
    {
        $this->expectException(HttpException::class);

        $client = new CurlHttpClient();
        $client->post(
            'https://this-host-does-not-exist.invalid/test',
            json_encode([]),
            ['Content-Type: application/json']
        );
    }
}
