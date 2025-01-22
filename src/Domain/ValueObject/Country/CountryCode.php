<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Country;

class CountryCode
{
    private string $value;

    public function __construct(string $value)
    {
        assert(strlen($value) === 2);

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
