<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\User;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\User\GetMetadataMapper;
use App\Domain\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GetMetadataController extends AbstractController
{
    public function __construct(
        private readonly GetMetadataMapper $getMetadataMapper,
    ) {
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(
        '/api/v1/user/metadata',
        name: 'v1_user_metadata',
        methods: 'GET',
    )]
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        if (!($user instanceof User)) {
            throw new \DomainException();
        }

        return $this->responseFactory->apiResponse(
            $this->getMetadataMapper->fromEntityToResponse($user)
        );
    }
}
