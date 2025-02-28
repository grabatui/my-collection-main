<?php

declare(strict_types=1);

namespace App\Tests\Core;

use App\Tests\Core\Trait\FakerTrait;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class UnitTestSuit extends TestCase
{
    use FakerTrait;

    protected Generator $faker;

    protected function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');
    }
}
