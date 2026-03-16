<?php

namespace App\Classes;

use App\Contracts\HttpClientInterface;

class Dadata
{
    private string $apiKey;
    private HttpClientInterface $httpClient;

    public function __construct(string $apiKey, HttpClientInterface $httpClient)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = $httpClient;
    }

    public function getCompanyDataByInn(string $inn): mixed
    {
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/findById/party';
        $result = $this->request($url, json_encode(['query' => $inn, 'branch_type' => 'MAIN']));

        if (isset($result->suggestions) && count($result->suggestions) > 0) {
            return $result->suggestions[0]->data;
        }

        return null;
    }

    public function getBankDataByBic(string $bic): mixed
    {
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/bank';
        $result = $this->request($url, json_encode(['query' => $bic]));

        if (isset($result->suggestions) && count($result->suggestions) > 0) {
            return $result->suggestions[0]->data;
        }

        return null;
    }

    public function searchCountry(string $country): ?array
    {
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/country';
        $result = $this->request($url, json_encode(['query' => $country]));

        if (isset($result->suggestions) && count($result->suggestions) > 0) {
            return array_map(fn($item) => $item->value ?? null, $result->suggestions);
        }

        return null;
    }

    public function searchAddress(string $search, ?array $locations = null): ?array
    {
        $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';
        $result = $this->request($url, json_encode(['query' => $search, 'locations' => $locations]));

        if (isset($result->suggestions) && count($result->suggestions) > 0) {
            return array_map(fn($item) => $item->value ?? null, $result->suggestions);
        }

        return null;
    }

    private function request(string $url, string $postData): mixed
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Token ' . $this->apiKey,
        ];

        $response = $this->httpClient->post($url, $postData, $headers);

        return json_decode($response);
    }
}
