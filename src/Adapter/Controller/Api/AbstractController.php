<?php

declare(strict_types=1);

namespace App\Adapter\Controller\Api;

use App\Adapter\Response\Core\ResponseFactory;
use App\Adapter\Response\Core\SuccessResponseDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Service\Attribute\Required;

class AbstractController extends BaseAbstractController
{
    #[Required]
    public ResponseFactory $responseFactory;

    protected function makeEmptyResponse(): JsonResponse
    {
        return $this->responseFactory->apiResponse(
            new SuccessResponseDto(),
        );
    }
}
