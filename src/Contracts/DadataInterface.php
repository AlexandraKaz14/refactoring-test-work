<?php

namespace App\Contracts;

interface DadataInterface
{
    public function getCompanyDataByInn(string $inn): object|null;

    public function getBankDataByBic(string $bic): object|null;

    public function searchCountry(string $country): ?array;

    public function searchAddress(string $search, ?array $locations = null): ?array;
}
