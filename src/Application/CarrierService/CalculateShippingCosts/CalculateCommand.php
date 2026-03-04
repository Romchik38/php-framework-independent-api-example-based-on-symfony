<?php

declare(strict_types=1);

namespace App\Application\CarrierService\CalculateShippingCosts;

use InvalidArgumentException;

final class CalculateCommand
{
    public const slugField = 'carrier';

    public const weightField = 'weightKg';

    public function __construct(
        public readonly string $carrierSlug,
        public readonly string $weight
    ) {}

    /**
     * @param array<string|int,mixed>
     *
     * @throws InvalidArgumentException
     */
    public static function fromHash(array $hash): self
    {
        $slug = $hash[self::slugField] ?? '';
        $weight = $hash[self::weightField] ?? '';
        if (gettype($slug) !== 'string') {
            $slug = (string) $slug;
        }
        if (gettype($weight) !== 'string') {
            $weight = (string) $weight;
        }

        return new self($slug, $weight);
    }
}
