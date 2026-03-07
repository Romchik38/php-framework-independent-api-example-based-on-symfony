<?php

declare(strict_types=1);

namespace App\Application\CarrierService;

use App\Application\CarrierService\CalculateShippingCosts\CalculateCommand;
use App\Application\CarrierService\CalculateShippingCosts\CalculateException;
use App\Application\CarrierService\CalculateShippingCosts\CalculateView;
use App\Application\CarrierService\List\ListDto;
use App\Application\CarrierService\List\ListException;
use App\Domain\Carrier\VO\Slug;
use App\Domain\Carrier\VO\Weight;
use InvalidArgumentException;

final class CarrierService
{
    public function __construct(
        private readonly CarrierRepositoryInterface $repository
    ) {}

    /**
     * @throws CalculateException - On database error
     * @throws InvalidArgumentException - Invalid user input
     * @throws NoSuchCarrierException - Invalid carrier
     */
    public function calculateShippingCosts(CalculateCommand $command): CalculateView
    {
        $slug = new Slug($command->carrierSlug);
        $weight = Weight::fromString($command->weight);

        try {
            $carrier = $this->repository->findCarrierBySlug($slug);
        } catch (RepositoryException $e) {
            throw new CalculateException($e->getMessage());
        }

        $price = $carrier->calculateShippingPriceByWeight($weight);

        return new CalculateView(
            $slug,
            $weight,
            $price
        );
    }

    /**
     * @return array<int, ListDto>
     *
     * @throws ListException
     */
    public function list(): array
    {
        try {
            $carriers = $this->repository->list();
        } catch (RepositoryException $e) {
            // log database error here
            throw new ListException('data storage error');
        }

        $dtos = [];
        foreach ($carriers as $carrier) {
            $dtos[] = new ListDto(
                $carrier->name,
                $carrier->slug
            );
        }

        return $dtos;
    }
}
