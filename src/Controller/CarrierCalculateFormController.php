<?php

namespace App\Controller;

use App\Application\CarrierService\CarrierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Application\CarrierService\CalculateShippingCosts\CalculateCommand;
use App\Application\CarrierService\CalculateShippingCosts\CalculateException;
use App\Application\CarrierService\NoSuchCarrierException;
use App\Controller\CarrierCalculateFormController\ErrorDto;
use App\Controller\CarrierCalculateFormController\SuccessDto;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class CarrierCalculateFormController extends AbstractController
{
    public function __construct(
        private readonly CarrierService $carrierService
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $carriers = $this->carrierService->list();

        return $this->render('carrier_calculate_form/index.html.twig', [
            'controller_name' => 'CarrierCalculateFormController',
            'carriers' => $carriers,
            'carrier_slug_field' => CalculateCommand::slugField,
            'carrier_weight_field' => CalculateCommand::weightField,
        ]);
    }

    #[Route('/api/shipping/calculate', methods: ['POST'], name: 'api.shipping.calculate')]
    public function calculate(Request $request): JsonResponse
    {
        $params = $request->request->all();
        try {
            $command = CalculateCommand::fromHash($params);
            $viewDto = $this->carrierService->calculateShippingCosts($command);
            $successDto = new SuccessDto($viewDto);

            return new JsonResponse($successDto);
        } catch (NoSuchCarrierException|InvalidArgumentException $e) {
            $errorDto = new ErrorDto($e->getMessage());

            return new JsonResponse($errorDto, 400);
        } catch (CalculateException $e) {
            // Do log if necessary.
            $errorDto = new ErrorDto('There is an error on our side, please try again later');

            return new JsonResponse($errorDto, 500);
        }
    }
}
