<?php

declare(strict_types=1);

namespace App\Domain\Service\Auth\SendResetPasswordRequest;

use App\Domain\Exception\Auth\TooManyRequestsException;
use App\Domain\Exception\DomainException;
use App\Domain\Exception\UnexpectedException;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Mail\Dto\HtmlTemplate;
use App\Domain\Service\Mail\MailSender;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

readonly class SendResetPasswordRequestService
{
    public function __construct(
        private UserRepository $userRepository,
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private MailSender $mailSender,
    ) {
    }

    public function execute(string $email): void
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException();
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $exception) {
            throw $this->mapException($exception);
        }

        $this->mailSender->send(
            to: $user->getEmail(),
            subject: 'Заявка на сброс пароля',
            htmlTemplate: new HtmlTemplate(
                template: 'email/reset_password.twig',
                variables: [
                    'userName' => $user->getName(),
                    'resetToken' => $resetToken->getToken(),
                ],
            ),
        );
    }

    private function mapException(ResetPasswordExceptionInterface $exception): DomainException
    {
        return match (get_class($exception)) {
            TooManyPasswordRequestsException::class => new TooManyRequestsException(previous: $exception),
            default => new UnexpectedException(previous: $exception),
        };
    }
}
