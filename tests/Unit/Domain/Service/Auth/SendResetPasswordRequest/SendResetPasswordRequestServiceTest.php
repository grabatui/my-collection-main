<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Service\Auth\SendResetPasswordRequest;

use App\Domain\Exception\Auth\TooManyRequestsException;
use App\Domain\Exception\User\UserNotFoundException;
use App\Domain\Repository\UserRepository;
use App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService;
use App\Domain\Service\Mail\Dto\HtmlTemplate;
use App\Domain\Service\Mail\MailSender;
use App\Tests\Core\Trait\Entity\UserTrait;
use App\Tests\Core\UnitTestSuit;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordToken;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelper;

/**
 * @covers \App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService
 */
class SendResetPasswordRequestServiceTest extends UnitTestSuit
{
    use UserTrait;

    /**
     * @covers \App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService
     */
    public function testHappyPath(): void
    {
        $email = $this->faker->email();

        $user = $this->makeUserEntity();
        $resetPasswordToken = new ResetPasswordToken(
            token: $this->getFakeWord(),
            expiresAt: new \DateTimeImmutable(),
            generatedAt: 0,
        );

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);
        $resetPasswordHelper = $this->createMock(ResetPasswordHelper::class);
        $resetPasswordHelper
            ->expects($this->once())
            ->method('generateResetToken')
            ->with($user)
            ->willReturn($resetPasswordToken);
        $mailSender = $this->createMock(MailSender::class);
        $mailSender
            ->expects($this->once())
            ->method('send')
            ->with(
                $user->getEmail(),
                $this->isType('string'),
                null,
                null,
                self::callback(function (HtmlTemplate $template) use ($user, $resetPasswordToken): bool {
                    $this->assertEquals(
                        [
                            'userName' => $user->getName(),
                            'resetToken' => $resetPasswordToken->getToken(),
                        ],
                        $template->variables,
                    );

                    return true;
                })
            );

        $service = new SendResetPasswordRequestService(
            $userRepository,
            $resetPasswordHelper,
            $mailSender,
        );

        $service->execute($email);
    }

    /**
     * @covers \App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService
     */
    public function testExceptionUserNotFound(): void
    {
        $email = $this->faker->email();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn(null);
        $resetPasswordHelper = $this->createMock(ResetPasswordHelper::class);
        $resetPasswordHelper
            ->expects($this->never())
            ->method('generateResetToken');
        $mailSender = $this->createMock(MailSender::class);
        $mailSender
            ->expects($this->never())
            ->method('send');

        $service = new SendResetPasswordRequestService(
            $userRepository,
            $resetPasswordHelper,
            $mailSender,
        );

        $this->expectException(UserNotFoundException::class);

        $service->execute($email);
    }

    /**
     * @covers \App\Domain\Service\Auth\SendResetPasswordRequest\SendResetPasswordRequestService
     */
    public function testExceptionResetPassword(): void
    {
        $email = $this->faker->email();

        $user = $this->makeUserEntity();

        $userRepository = $this->createMock(UserRepository::class);
        $userRepository
            ->expects($this->once())
            ->method('findByEmail')
            ->with($email)
            ->willReturn($user);
        $resetPasswordHelper = $this->createMock(ResetPasswordHelper::class);
        $resetPasswordHelper
            ->expects($this->once())
            ->method('generateResetToken')
            ->with($user)
            ->willThrowException(
                new TooManyPasswordRequestsException(new \DateTimeImmutable())
            );
        $mailSender = $this->createMock(MailSender::class);
        $mailSender
            ->expects($this->never())
            ->method('send');

        $service = new SendResetPasswordRequestService(
            $userRepository,
            $resetPasswordHelper,
            $mailSender,
        );

        $this->expectException(TooManyRequestsException::class);

        $service->execute($email);
    }
}
