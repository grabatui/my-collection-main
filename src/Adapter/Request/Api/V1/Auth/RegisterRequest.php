<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Auth;

use App\Infrastructure\Http\Constraint\User\UserNotExistsByEmail;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        #[UserNotExistsByEmail]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength(minScore: Assert\PasswordStrength::STRENGTH_WEAK)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\IdenticalTo(propertyPath: 'password', message: 'Пароли не равны')]
        public string $passwordRepeat,
    ) {
    }
}
