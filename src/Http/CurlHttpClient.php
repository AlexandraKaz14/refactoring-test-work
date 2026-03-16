<?php

namespace App\Http;

use App\Contracts\HttpClientInterface;

class CurlHttpClient implements HttpClientInterface
{
    public function post(string $url, string $body, array $headers): string
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_TIMEOUT        => 40,
            CURLOPT_CONNECTTIMEOUT => 20,
            CURLOPT_ENCODING       => '',
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 10,
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response ?: '';
    }
}
