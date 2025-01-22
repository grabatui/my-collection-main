<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\Repository\CountryRepository;
use App\Domain\ValueObject\Country\CountryCode;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ORM\Cache(usage: 'NONSTRICT_READ_WRITE', region: 'dictionaries')]
class Country
{
    #[ORM\Id]
    #[ORM\Column(type: Types::STRING)]
    private string $code;

    #[ORM\Column(type: Types::STRING)]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    private string $englishName;

    public function getCode(): string
    {
        return $this->code;
    }

    public static function create(
        CountryCode $code,
        string $name,
        string $englishName,
    ): self {
        $country = new self();
        $country->setCode((string)$code);
        $country->setName($name);
        $country->setEnglishName($englishName);

        return $country;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEnglishName(): string
    {
        return $this->englishName;
    }

    public function setEnglishName(string $englishName): void
    {
        $this->englishName = $englishName;
    }
}
