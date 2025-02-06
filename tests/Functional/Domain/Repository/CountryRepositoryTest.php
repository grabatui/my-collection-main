<?php

declare(strict_types=1);

namespace App\Tests\Functional\Domain\Repository;

use App\Domain\Entity\Country;
use App\Domain\Repository\CountryRepository;
use App\Tests\Core\KernelTestSuit;
use App\Tests\Core\Trait\Entity\CountryTrait;

/**
 * @covers \App\Domain\Repository\CountryRepository
 */
class CountryRepositoryTest extends KernelTestSuit
{
    use CountryTrait;

    private const string CUSTOM_CODE = 'zz';

    protected function tearDown(): void
    {
        parent::tearDown();

        $country = $this->getCountryRepository()->findOneBy(['code' => self::CUSTOM_CODE]);

        if ($country) {
            $this->getEntityManager()->remove($country);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @covers \App\Domain\Repository\CountryRepository::getByCodes
     */
    public function testHappyPathGetByCodes(): void
    {
        $country1 = $this->findOrCreateCountryToDatabase();
        $country2 = $this->findOrCreateCountryToDatabase();

        $repository = $this->getCountryRepository();

        $actualCountries = $repository->getByCodes([
            $country1->getCode(),
            $country2->getCode(),
            'zz',
        ]);

        $this->assertArrayOfEntitiesEqual(
            expectedEntities: [$country1, $country2],
            actualEntities: $actualCountries,
            sortFunction: static fn (Country $aCountry, Country $bCountry): int =>
                $aCountry->getCode() <=> $bCountry->getCode()
            ,
        );
    }

    /**
     * @covers \App\Domain\Repository\CountryRepository::getAllByCodes
     */
    public function testHappyPathGetAllByCodes(): void
    {
        $country1 = $this->findOrCreateCountryToDatabase();
        $country2 = $this->findOrCreateCountryToDatabase();

        $repository = $this->getCountryRepository();

        $actualCountries = $repository->getAllByCodes();

        $this->assertArrayHasKey($country1->getCode(), $actualCountries);
        $this->assertArrayHasKey($country2->getCode(), $actualCountries);
    }

    /**
     * @covers \App\Domain\Repository\CountryRepository::save
     */
    public function testHappyPathSave(): void
    {
        $country = $this->makeCountryEntity(self::CUSTOM_CODE);

        $repository = $this->getCountryRepository();

        $this->assertNull(
            $repository->findOneBy(['code' => $country->getCode()])
        );

        $repository->save($country);

        $this->assertNotNull(
            $repository->findOneBy(['code' => $country->getCode()])
        );
    }

    private function getCountryRepository(): CountryRepository
    {
        /** @var CountryRepository $repository */
        $repository = $this->getService(CountryRepository::class);

        return $repository;
    }
}
