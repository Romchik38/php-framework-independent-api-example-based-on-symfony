<?php

declare(strict_types=1);

namespace App\Application\CarrierService\List;

use App\Domain\Carrier\VO\Name;
use App\Domain\Carrier\VO\Slug;

final class ListDto
{
    public function __construct(
        public readonly Name $carrierName,
        public readonly Slug $carrierSlug,
    ) {}

    public function getName(): string
    {
        return $this->carrierName->value;
    }

    public function getSlug(): string
    {
        return $this->carrierSlug->value;
    }
}
