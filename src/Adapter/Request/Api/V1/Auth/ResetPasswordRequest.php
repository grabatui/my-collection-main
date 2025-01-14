<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Auth;

use App\Infrastructure\Http\Constraint\User\UserNotExistsByEmail;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $token,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_WEAK)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\IdenticalTo(propertyPath: 'password', message: 'Пароли не равны')]
        public string $passwordRepeat,
    ) {
    }
}
