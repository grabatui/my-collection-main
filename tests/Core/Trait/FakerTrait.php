<?php

declare(strict_types=1);

namespace App\Tests\Core\Trait;

trait FakerTrait
{
    public function makeUniqueValueWithCallback(
        callable $createCallback,
        callable $searchCallback,
    ): string|int {
        do {
            $newValue = $createCallback();

            $foundEntity = $searchCallback($newValue);
        } while (!empty($foundEntity));

        return $newValue;
    }

    public function getFakeStrictUuid(string $letter): string
    {
        return str_replace(
            '#',
            $letter,
            '########-####-####-####-############',
        );
    }

    public function getFakeNumber(
        int $length = 6,
        bool $isStrict = true,
    ): int {
        return $this->faker->randomNumber($length, $isStrict);
    }

    public function getFakeWord(
        int $length = 7,
    ): string {
        return $this->faker->lexify(
            str_repeat('?', $length),
        );
    }

    /**
     * @template EnumClass
     *
     * @param class-string<EnumClass> $enumClass
     *
     * @return EnumClass
     */
    public function getFakeEnum(
        string $enumClass,
    ): \BackedEnum {
        return $this->faker->randomElement(
            $enumClass::cases(),
        );
    }

    public function getRandomCallbackItems(
        callable $callback,
        int $min = 1,
        int $max = 3,
        ?int $exact = null,
    ): array {
        $count = $exact ?: random_int($min, $max);

        $result = [];
        for ($i = 0; $i < $count; ++$i) {
            $result[] = $callback();
        }

        return $result;
    }

    /**
     * @template ElementType
     *
     * @param ElementType[] $elements
     *
     * @return ElementType|null
     */
    public function getRandomArrayElementAndDeleteIt(
        array &$elements,
        bool $resetKeys = true,
    ): mixed {
        if (empty($elements)) {
            return null;
        }

        $randomKey = random_int(0, count($elements) - 1);

        $element = $elements[$randomKey];

        unset($elements[$randomKey]);

        if ($resetKeys) {
            $elements = array_values($elements);
        }

        return $element;
    }
}
