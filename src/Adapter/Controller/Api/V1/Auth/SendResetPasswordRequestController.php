<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Adapter\Request\Api\V1\Auth\SendResetPasswordRequestRequest;
use App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class SendResetPasswordRequestController extends AbstractController
{
    public function __construct(
        private readonly SendResetPasswordRequestService $sendResetPasswordRequestService,
    ) {}

    #[Route(
        '/api/v1/auth/send-reset-password-request',
        name: 'v1_auth_send_reset_password_request',
        methods: 'POST',
    )]
    public function __invoke(
        #[MapRequestPayload] SendResetPasswordRequestRequest $request,
    ): JsonResponse {
        $this->sendResetPasswordRequestService->execute($request->email);

        return $this->makeEmptyResponse();
    }
}
