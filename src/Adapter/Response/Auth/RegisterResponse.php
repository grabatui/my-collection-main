<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth;

use App\Adapter\Response\Auth\RegisterResponse\RegisterResponseData;
use App\Adapter\Response\Core\SuccessResponseDto;

readonly class RegisterResponse extends SuccessResponseDto
{
    public function __construct(
        public RegisterResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
