<?php

namespace Tests\Unit;

use App\Contracts\DadataInterface;
use App\Http\Controllers\DadataController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class DadataControllerTest extends TestCase
{
    private DadataInterface&MockObject $dadata;
    private DadataController $controller;

    protected function setUp(): void
    {
        $this->dadata = $this->createMock(DadataInterface::class);
        $this->controller = new DadataController($this->dadata);
    }

    public function testInnDelegatesToDadata(): void
    {
        $expected = (object)['inn' => '7707083893'];

        $this->dadata->expects($this->once())
            ->method('getCompanyDataByInn')
            ->with('7707083893')
            ->willReturn($expected);

        $result = $this->controller->inn('7707083893');

        $this->assertSame($expected, $result);
    }

    public function testBankDelegatesToDadata(): void
    {
        $expected = (object)['bic' => '044525225'];

        $this->dadata->expects($this->once())
            ->method('getBankDataByBic')
            ->with('044525225')
            ->willReturn($expected);

        $result = $this->controller->bank('044525225');

        $this->assertSame($expected, $result);
    }

    public function testCountryDelegatesToDadata(): void
    {
        $expected = ['Россия', 'Белоруссия'];

        $this->dadata->expects($this->once())
            ->method('searchCountry')
            ->with('Росс')
            ->willReturn($expected);

        $result = $this->controller->country('Росс');

        $this->assertSame($expected, $result);
    }

    public function testAddressDelegatesToDadata(): void
    {
        $expected = ['г Москва, ул Ленина, д 1'];

        $this->dadata->expects($this->once())
            ->method('searchAddress')
            ->with('Москва', null)
            ->willReturn($expected);

        $result = $this->controller->address('Москва');

        $this->assertSame($expected, $result);
    }
}
