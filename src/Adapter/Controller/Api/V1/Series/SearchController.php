<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Series;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\Series\SearchMapper;
use App\Adapter\Request\Api\V1\Series\SearchRequest;
use App\Domain\Service\Series\SearchService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    public function __construct(
        private readonly SearchService $searchService,
        private readonly SearchMapper $searchMapper,
    ) {
    }

    #[Route(
        '/api/v1/series/search',
        name: 'v1_series_search',
        methods: 'GET',
    )]
    public function __invoke(
        #[MapQueryString] SearchRequest $request,
    ): JsonResponse {
        $result = $this->searchService->execute(
            query: $request->query,
            page: $request->page,
        );

        return $this->responseFactory->apiResponse(
            $this->searchMapper->fromDtoToResponse($result),
        );
    }
}
