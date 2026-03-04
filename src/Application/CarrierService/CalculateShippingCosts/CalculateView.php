<?php

declare(strict_types=1);

namespace App\Application\CarrierService\CalculateShippingCosts;

use App\Domain\Carrier\VO\Price;
use App\Domain\Carrier\VO\Slug;
use App\Domain\Carrier\VO\Weight;
use JsonSerializable;

final class CalculateView implements JsonSerializable
{
    public const PRICE_FIELD = 'price';

    public const CURRENCY_FIELD = 'currency';

    public function __construct(
        public readonly Slug $carrierSlug,
        public readonly Weight $weight,
        public readonly Price $price
    ) {}

    public function getSlug(): string
    {
        return $this->carrierSlug->value;
    }

    public function getWeight(): float
    {
        return $this->weight->value;
    }

    public function getPrice(): float
    {
        return $this->price->value;
    }

    public function jsonSerialize(): mixed
    {
        return [
            CalculateCommand::slugField => $this->getSlug(),
            CalculateCommand::weightField => $this->getWeight(),
            $this::CURRENCY_FIELD => 'EUR',
            $this::PRICE_FIELD => $this->getPrice(),
        ];
    }
}
