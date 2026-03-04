<?php

declare(strict_types=1);

namespace App\Application\CarrierService;

use App\Application\CarrierService\CalculateShippingCosts\CalculateCommand;
use App\Application\CarrierService\CalculateShippingCosts\CalculateException;
use App\Application\CarrierService\CalculateShippingCosts\CalculateView;
use App\Application\CarrierService\CalculateShippingCosts\ListException;
use App\Application\CarrierService\List\ListDto;
use App\Domain\Carrier\VO\Slug;
use App\Domain\Carrier\VO\Weight;
use InvalidArgumentException;

final class CarrierService
{
    public function __construct(
        private readonly CarrierRepositoryInterface $repository
    ) {}

    /**
     * @throws CalculateException
     */
    public function calculateShippingCosts(CalculateCommand $command): CalculateView
    {
        try {
            $slug = new Slug($command->carrierSlug);
            $weight = Weight::fromString($command->weight);
            $carrier = $this->repository->findCarrierBySlug($slug);
        } catch (NoSuchCarrierException|InvalidArgumentException  $e) {
            // display a message to user
            throw new CalculateException($e->getMessage());
        } catch (RepositoryException $e) {
            // log database error here, do something with $e
            // display a message to user
            throw new CalculateException('data storage error');
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
