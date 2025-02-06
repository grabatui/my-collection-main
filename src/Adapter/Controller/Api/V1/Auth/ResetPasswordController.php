<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Request\Api\V1\Auth\ResetPasswordRequest;
use App\Domain\Service\Auth\ResetPassword\ResetPasswordService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        private ResetPasswordService $resetPasswordService,
    ) {
    }

    #[Route(
        '/api/v1/auth/reset-password',
        name: 'v1_auth_reset_password',
        methods: 'POST',
    )]
    public function __invoke(
        #[MapRequestPayload] ResetPasswordRequest $request,
    ): JsonResponse {
        $this->resetPasswordService->execute(
            token: $request->token,
            password: $request->password,
        );

        return $this->makeEmptyResponse();
    }
}
