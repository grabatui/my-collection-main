<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Constraint\User;

use App\Domain\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserNotExistsByEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!($constraint instanceof UserNotExistsByEmail)) {
            return;
        }

        $alreadyExistsUser = $this->userRepository->findByEmail($value);

        if ($alreadyExistsUser) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
