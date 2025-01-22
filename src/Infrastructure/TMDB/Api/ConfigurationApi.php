<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB\Api;

use Tmdb\Api\AbstractApi;

class ConfigurationApi extends AbstractApi
{
    public function getCountries(array $headers = []): array
    {
        return $this->get('configuration/countries', [], $headers);
    }

    public function getJobs(array $headers = []): array
    {
        return $this->get('configuration/jobs', [], $headers);
    }
}
