<?php

declare(strict_types=1);

namespace App\Adapter\Command\Dictionary;

use App\Domain\Entity\Country;
use App\Domain\Repository\CountryRepository;
use App\Domain\Service\Dictionary\Loader\DictionaryLoaderInterface;
use App\Domain\ValueObject\Country\CountryCode;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:dictionary:init:countries',
)]
class InitCountriesCommand extends Command
{
    public function __construct(
        private readonly CountryRepository $countryRepository,
        private readonly DictionaryLoaderInterface $dictionaryLoader,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $countries = $this->dictionaryLoader->getCountries();

        if (empty($countries)) {
            return self::FAILURE;
        }

        $remoteCountriesByCode = [];
        foreach ($countries as $country) {
            $remoteCountriesByCode[$country->iso3166Code] = $country;
        }

        $alreadyExists = $this->countryRepository->getByCodes(
            array_keys($remoteCountriesByCode),
        );

        foreach ($remoteCountriesByCode as $code => $country) {
            if (isset($alreadyExists[$code])) {
                $entity = $alreadyExists[$code];

                $entity->setName($country->nativeName);
                $entity->setEnglishName($country->englishName);
            } else {
                $entity = Country::create(
                    code: new CountryCode($code),
                    name: $country->nativeName,
                    englishName: $country->englishName,
                );
            }

            $this->countryRepository->save($entity);
        }

        return self::SUCCESS;
    }
}
