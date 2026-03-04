<?php

declare(strict_types=1);

namespace App\Application\CarrierService\ShippingCostCalculators;

use App\Domain\Carrier\ShippingCostCalculatorInterface;
use App\Domain\Carrier\VO\Price;
use App\Domain\Carrier\VO\Weight;
use RoundingMode;

final class PackGroupCalculator implements ShippingCostCalculatorInterface
{
    public function calculateShippingCosts(Weight $weight): Price
    {
        $weight = round($weight->value, 0, RoundingMode::AwayFromZero);

        return new Price($weight);
    }
}
