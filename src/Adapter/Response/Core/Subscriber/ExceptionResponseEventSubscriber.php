<?php

declare(strict_types=1);

namespace App\Adapter\Response\Core\Subscriber;

use App\Adapter\Response\Core\ErrorResponseDto;
use App\Adapter\Response\Core\ResponseFactory;
use App\Domain\Exception\DomainException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionResponseEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected readonly ResponseFactory $responseFactory,
        protected bool $isShowInternalException = false,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            if ($exception->getPrevious() instanceof ValidationFailedException) {
                /** @var ValidationFailedException $violationsException */
                $violationsException = $exception->getPrevious();

                $response = $this->responseFactory->fromValidatorViolationsResponse(
                    $violationsException->getViolations()
                );
            } else {
                $response = $this->responseFactory->apiResponse(
                    data: $this->responseFactory->badRequestDto($exception->getMessage()),
                    status: $exception->getStatusCode(),
                    headers: $exception->getHeaders(),
                );
            }

            $event->setResponse($response);

            return;
        }

        if ($exception instanceof DomainException) {
            $event->setResponse(
                $this->responseFactory->apiResponse(
                    data: new ErrorResponseDto(
                        resultCode: $exception->getErrorCode(),
                        message: $exception->getErrorMessage() ?: $exception->getMessage(),
                    ),
                    status: Response::HTTP_UNPROCESSABLE_ENTITY,
                ),
            );

            return;
        }

        // Internal (500)
        $event->setResponse(
            $this->responseFactory->criticalErrorResponse(
                message: $this->isShowInternalException ? $exception->getMessage() : null,
            ),
        );
    }
}