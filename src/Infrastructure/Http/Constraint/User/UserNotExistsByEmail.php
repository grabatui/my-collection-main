<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Constraint\User;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UserNotExistsByEmail extends Constraint
{
    public string $message = 'Пользователь c email "{{ value }}" уже зарегистрирован в системе';
}
