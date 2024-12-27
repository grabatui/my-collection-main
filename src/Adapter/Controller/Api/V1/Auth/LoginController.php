<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Mapper\Auth\LoginMapper;
use App\Adapter\Request\Api\V1\Auth\LoginRequest;
use App\Domain\Service\AccessToken\GenerateAccessTokenService;
use App\Domain\Service\Auth\Login\LoginService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    public function __construct(
        private readonly LoginMapper $loginMapper,
        private readonly LoginService $loginService,
        private readonly GenerateAccessTokenService $generateAccessTokenService,
    ) {
    }

    #[Route(
        '/api/v1/auth/login',
        name: 'v1_auth_login',
        methods: 'POST',
    )]
    public function __invoke(
        #[MapRequestPayload] LoginRequest $request,
        Request $baseRequest,
    ): JsonResponse {
        $user = $this->loginService->execute(
            dto: $this->loginMapper->fromQueryToDto($request),
            clientIp: $baseRequest->getClientIp(),
        );

        $accessToken = $this->generateAccessTokenService->execute($user);

        return $this->responseFactory->apiResponse(
            $this->loginMapper->fromDtoToResponse($accessToken),
        );
    }
}
