<?php

declare(strict_types=1);

namespace App\Adapter\Response\Auth;

use App\Adapter\Response\Auth\RefreshTokenResponse\RefreshTokenResponseData;
use App\Adapter\Response\Core\SuccessResponseDto;

readonly class RefreshTokenResponse extends SuccessResponseDto
{
    public function __construct(
        public RefreshTokenResponseData $data,
        string $resultCode = 'success',
    ) {
        parent::__construct($resultCode);
    }
}
