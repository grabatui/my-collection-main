<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth;

use App\Adapter\Response\Auth\LoginResponse\LoginResponseData;
use App\Adapter\Response\Core\SuccessResponseDto;

readonly class LoginResponse extends SuccessResponseDto
{
    public function __construct(
        public LoginResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
