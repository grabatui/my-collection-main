<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Series;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\Series\GetDashboardMapper;
use App\Adapter\Request\Api\V1\Series\GetDashboardRequest;
use App\Domain\Service\Series\GetDashboardService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class GetDashboardController extends AbstractController
{
    public function __construct(
        private readonly GetDashboardService $getDashboardService,
        private readonly GetDashboardMapper $getDashboardMapper,
    ) {
    }

    #[Route(
        '/api/v1/series/dashboard',
        name: 'v1_series_dashboard',
        methods: 'GET',
    )]
    public function __invoke(
        #[MapQueryString] GetDashboardRequest $request,
    ): JsonResponse {
        $result = $this->getDashboardService->execute(
            page: $request->page,
        );

        return $this->responseFactory->apiResponse(
            $this->getDashboardMapper->fromDtoToResponse($result),
        );
    }
}
