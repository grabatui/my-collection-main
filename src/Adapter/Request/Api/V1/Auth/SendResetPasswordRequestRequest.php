<?php

declare(strict_types=1);

namespace App\Adapter\Request\Api\V1\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class SendResetPasswordRequestRequest
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        public string $email,
    ) {}
}
