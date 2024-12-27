<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core;

use App\Adapter\Response\Core\Dto\ValidationErrorViolationDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ResponseFactory
{
    private const string CRITICAL_EXCEPTION_MESSAGE = 'Неизвестная ошибка приложения. Обратитесь в техническую поддержку';

    public function __construct(
        protected SerializerInterface $serializer,
    ) {
    }

    public function fromValidatorViolationsResponse(ConstraintViolationListInterface $violations): JsonResponse
    {
        $items = [];
        $messages = [];
        foreach ($violations as $item) {
            $items[] = new ValidationErrorViolationDto(
                path: $item->getPropertyPath(),
                message: $item->getMessage(),
            );

            $messages[] = $item->getMessage();
        }

        $response = new ValidationResponseDto(
            resultCode: 'validation_error',
            message: implode(' ', $messages),
            data: $items,
        );

        return $this->apiResponse($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function badRequestDto(
        string $message,
        string $code = 'bad_request',
        array $data = [],
    ): ErrorResponseDto {
        return new ErrorResponseDto(
            resultCode: $code,
            message: $message,
            data: $data,
        );
    }

    public function criticalErrorResponse(?string $message = null): JsonResponse
    {
        $response = new ErrorResponseDto(
            resultCode: 'internal_error',
            message: $message ?: self::CRITICAL_EXCEPTION_MESSAGE,
        );

        return $this->apiResponse($response, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param array<string, string|string[]> $headers
     */
    public function apiResponse(mixed $data, int $status = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse($this->jsonSerialize($data), $status, $headers, true);
    }

    public function jsonSerialize(mixed $data): string
    {
        return $this->serializer->serialize($data, 'json', [
            JsonEncode::OPTIONS => JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT,
        ]);
    }
}
