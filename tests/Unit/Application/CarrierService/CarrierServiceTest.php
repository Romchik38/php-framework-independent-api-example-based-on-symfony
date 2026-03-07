<?php

declare(strict_types=1);

namespace App\Tests\Unit\Application\CarrierService;

use App\Application\CarrierService\CalculateShippingCosts\CalculateCommand;
use PHPUnit\Framework\TestCase;
use App\Application\CarrierService\CarrierService;
use App\Application\CarrierService\NoSuchCarrierException;
use App\Domain\Carrier\Carrier;
use App\Domain\Carrier\VO\Name;
use App\Domain\Carrier\VO\Price;
use App\Domain\Carrier\VO\Slug;
use App\Tests\Unit\Application\CarrierService\Helpers\CarrierCalculator;
use App\Tests\Unit\Application\CarrierService\Helpers\CarrierRepository;

class CarrierServiceTest extends TestCase
{
    public function testCalculateShippingCosts(): void
    {
        $carrierSlug = 'Carrier1';
        $carrierWeight = '2.5';
        $command = new CalculateCommand($carrierSlug, $carrierWeight);

        $calculator = new CarrierCalculator(new Price(10.0));
        $carrier = new Carrier(
            new Name('Carrier 1'),
            new Slug($carrierSlug),
            $calculator
        );
        $repository = new CarrierRepository([$carrier]);

        $carrierService = new CarrierService($repository);

        $calculateView = $carrierService->calculateShippingCosts($command);

        $this->assertSame(10.0, $calculateView->price->value);
    }

    public function testCalculateShippingCostsNoSuchCarrier(): void
    {
        $carrierSlug = 'carrier1';
        $carrierWeight = '2.5';
        $command = new CalculateCommand($carrierSlug, $carrierWeight);

        $calculator = new CarrierCalculator(new Price(10.0));
        $carrier = new Carrier(
            new Name('Carrier 2'),
            new Slug('carrier2'),
            $calculator
        );
        $repository = new CarrierRepository([$carrier]);

        $carrierService = new CarrierService($repository);

        $this->expectException(NoSuchCarrierException::class);
        $carrierService->calculateShippingCosts($command);
    }
}
