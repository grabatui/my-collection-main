<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api\V1\Auth;

use App\Adapter\Controller\Api\AbstractController;
use App\Domain\Entity\User;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Service\Auth\Logout\LogoutService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LogoutController extends AbstractController
{
    public function __construct(
        private readonly LogoutService $logoutService,
    ) {
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route(
        '/api/v1/auth/logout',
        name: 'v1_auth_logout',
        methods: 'POST',
    )]
    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        if (!($user instanceof User)) {
            throw new UserNotFoundException();
        }

        $this->logoutService->execute($user);

        return $this->makeEmptyResponse();
    }
}
