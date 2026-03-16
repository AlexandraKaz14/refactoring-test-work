<?php

namespace App;

use App\Services\Dadata;

class DadataController
{
    public function __construct(private readonly Dadata $dadata)
    {
    }

    public function inn(string $query): mixed
    {
        return $this->dadata->getCompanyDataByInn($query);
    }

    public function bank(string $query): mixed
    {
        return $this->dadata->getBankDataByBic($query);
    }

    public function country(string $query): ?array
    {
        return $this->dadata->searchCountry($query);
    }

    public function address(string $query, ?array $locations = null): ?array
    {
        return $this->dadata->searchAddress($query, $locations);
    }
}
