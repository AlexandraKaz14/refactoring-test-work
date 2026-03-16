<?php

namespace App\Http\Controllers;

use App\Contracts\DadataInterface;

class DadataController
{
    public function __construct(private readonly DadataInterface $dadata)
    {
    }

    public function inn(string $query): object|null
    {
        return $this->dadata->getCompanyDataByInn($query);
    }

    public function bank(string $query): object|null
    {
        return $this->dadata->getBankDataByBic($query);
    }

    public function country(string $query): array
    {
        return $this->dadata->searchCountry($query);
    }

    public function address(string $query, ?array $locations = null): array
    {
        return $this->dadata->searchAddress($query, $locations);
    }
}
