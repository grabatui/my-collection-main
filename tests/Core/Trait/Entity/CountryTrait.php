<?php

declare(strict_types=1);

namespace App\Tests\Core\Trait\Entity;

use App\Domain\Entity\Country;
use App\Domain\Repository\CountryRepository;
use App\Domain\ValueObject\Country\CountryCode;

trait CountryTrait
{
    public function makeCountryEntity(
        ?string $code = null,
    ): Country {
        return Country::create(
            code: new CountryCode($code ?: $this->faker->countryCode()),
            name: $this->faker->word(),
            englishName: $this->faker->word(),
        );
    }

    public function findOrCreateCountryToDatabase(
        ?string $code = null,
    ): Country {
        $code = $code ?: $this->faker->countryCode();

        $country = $this->getService(CountryRepository::class)->findOneBy(['code' => $code]);

        if ($country) {
            return $country;
        }

        $country = $this->makeCountryEntity($code);

        $this->getEntityManager()->persist($country);
        $this->getEntityManager()->flush();

        return $country;
    }
}
