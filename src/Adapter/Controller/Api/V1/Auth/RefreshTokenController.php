<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\Auth\RefreshTokenMapper;
use App\Adapter\Request\Api\V1\Auth\RefreshTokenRequest;
use App\Domain\Service\AccessToken\GenerateAccessTokenService;
use App\Domain\Service\Auth\RefreshToken\RefreshTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class RefreshTokenController extends AbstractController
{
    public function __construct(
        private readonly RefreshTokenMapper $refreshTokenMapper,
        private readonly RefreshTokenService $refreshTokenService,
        private readonly GenerateAccessTokenService $generateAccessTokenService,
    ) {
    }

    #[Route(
        '/api/v1/auth/refresh-token',
        name: 'v1_auth_refresh_token',
        methods: 'POST',
    )]
    public function __invoke(
        #[MapRequestPayload] RefreshTokenRequest $request,
        Request $baseRequest,
    ): JsonResponse {
        $user = $this->refreshTokenService->execute(
            refreshToken: $request->refreshToken,
            clientIp: $baseRequest->getClientIp(),
        );

        $accessToken = $this->generateAccessTokenService->execute($user);

        return $this->responseFactory->apiResponse(
            $this->refreshTokenMapper->fromDtoToResponse($accessToken),
        );
    }
}
