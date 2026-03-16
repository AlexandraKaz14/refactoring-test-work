<?php

namespace Tests\Unit;

use App\Services\Dadata;
use App\Contracts\HttpClientInterface;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DadataTest extends TestCase
{
    private HttpClientInterface&MockObject $httpClient;
    private Dadata $dadata;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->dadata = new Dadata('test-api-key', $this->httpClient);
    }

    public function testGetCompanyDataByInnReturnsData(): void
    {
        $response = json_encode([
            'suggestions' => [
                ['data' => ['inn' => '1234567890', 'name' => ['short_with_opf' => 'ЗАО "Рога и копыта"']]]
            ]
        ]);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $result = $this->dadata->getCompanyDataByInn('1234567890');

        $this->assertNotNull($result);
        $this->assertEquals('1234567890', $result->inn);
    }

    public function testGetCompanyDataByInnReturnsNullWhenNoSuggestions(): void
    {
        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn(json_encode(['suggestions' => []]));

        $result = $this->dadata->getCompanyDataByInn('0000000000');

        $this->assertNull($result);
    }

    public function testGetBankDataByBicReturnsData(): void
    {
        $response = json_encode([
            'suggestions' => [
                ['data' => ['bic' => '123456789', 'name' => ['short' => 'Банк "Рога и копыта"']]]
            ]
        ]);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $result = $this->dadata->getBankDataByBic('123456789');

        $this->assertNotNull($result);
        $this->assertEquals('123456789', $result->bic);
    }

    public function testGetBankDataByBicReturnsNullWhenNoSuggestions(): void
    {
        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn(json_encode(['suggestions' => []]));

        $result = $this->dadata->getBankDataByBic('000000000');

        $this->assertNull($result);
    }

    public function testSearchCountryReturnsArray(): void
    {
        $response = json_encode([
            'suggestions' => [
                ['value' => 'Россия'],
                ['value' => 'Белоруссия'],
            ]
        ]);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $result = $this->dadata->searchCountry('Росс');

        $this->assertIsArray($result);
        $this->assertContains('Россия', $result);
    }

    public function testSearchCountryReturnsNullWhenNoSuggestions(): void
    {
        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn(json_encode(['suggestions' => []]));

        $result = $this->dadata->searchCountry('xyz');

        $this->assertNull($result);
    }

    public function testSearchAddressReturnsArray(): void
    {
        $response = json_encode([
            'suggestions' => [
                ['value' => 'г Москва, ул Ленина, д 1'],
            ]
        ]);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn($response);

        $result = $this->dadata->searchAddress('Москва Ленина');

        $this->assertIsArray($result);
        $this->assertContains('г Москва, ул Ленина, д 1', $result);
    }

    public function testSearchAddressReturnsNullWhenNoSuggestions(): void
    {
        $this->httpClient->expects($this->once())
            ->method('post')
            ->willReturn(json_encode(['suggestions' => []]));

        $result = $this->dadata->searchAddress('xyzxyzxyz');

        $this->assertNull($result);
    }

    public function testSearchAddressPassesLocations(): void
    {
        $locations = [['country_iso_code' => 'RU']];

        $response = json_encode([
            'suggestions' => [
                ['value' => 'г Москва, ул Ленина, д 1'],
            ]
        ]);

        $this->httpClient->expects($this->once())
            ->method('post')
            ->with(
                $this->stringContains('suggest/address'),
                $this->callback(fn($body) => json_decode($body, true)['locations'] === $locations),
                $this->anything()
            )
            ->willReturn($response);

        $result = $this->dadata->searchAddress('Москва', $locations);

        $this->assertIsArray($result);
        $this->assertContains('г Москва, ул Ленина, д 1', $result);
    }
}
