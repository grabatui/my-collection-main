<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\Auth\RegisterMapper;
use App\Adapter\Request\Api\V1\Auth\RegisterRequest;
use App\Domain\Service\AccessToken\GenerateAccessTokenService;
use App\Domain\Service\Registration\RegisterService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private readonly RegisterMapper $registerMapper,
        private readonly RegisterService $registerService,
        private readonly GenerateAccessTokenService $generateAccessTokenService,
    ) {
    }

    #[Route(
        '/api/v1/auth/register',
        name: 'auth_register',
        methods: 'POST',
    )]
    public function __invoke(
        #[MapRequestPayload] RegisterRequest $request,
    ): JsonResponse {
        $user = $this->registerService->execute(
            $this->registerMapper->fromQueryToDto($request)
        );

        $accessToken = $this->generateAccessTokenService->execute($user);

        return $this->responseFactory->apiResponse(
            $this->registerMapper->fromDtoToResponse($accessToken)
        );
    }
}
