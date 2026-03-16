<?php

namespace App\Services;

use App\Contracts\DadataInterface;
use App\Contracts\HttpClientInterface;

class Dadata implements DadataInterface
{
    private const BASE_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/';

    public function __construct(
        private readonly string $apiKey,
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    public function getCompanyDataByInn(string $inn): object|null
    {
        $result = $this->request('findById/party', ['query' => $inn, 'branch_type' => 'MAIN']);

        return $this->parseFirstSuggestion($result);
    }

    public function getBankDataByBic(string $bic): object|null
    {
        $result = $this->request('suggest/bank', ['query' => $bic]);

        return $this->parseFirstSuggestion($result);
    }

    public function searchCountry(string $country): ?array
    {
        $result = $this->request('suggest/country', ['query' => $country]);

        return $this->parseSuggestionValues($result);
    }

    public function searchAddress(string $search, ?array $locations = null): ?array
    {
        $result = $this->request('suggest/address', ['query' => $search, 'locations' => $locations]);

        return $this->parseSuggestionValues($result);
    }

    private function parseFirstSuggestion(object $result): object|null
    {
        $suggestions = $result->suggestions ?? [];

        return count($suggestions) > 0 ? $suggestions[0]->data : null;
    }

    private function parseSuggestionValues(object $result): ?array
    {
        $suggestions = $result->suggestions ?? [];

        if (count($suggestions) === 0) {
            return null;
        }

        return array_map(fn($item) => $item->value ?? null, $suggestions);
    }

    private function request(string $endpoint, array $data): object
    {
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Token ' . $this->apiKey,
        ];

        $response = $this->httpClient->post(self::BASE_URL . $endpoint, json_encode($data), $headers);

        return json_decode($response) ?? new \stdClass();
    }
}
