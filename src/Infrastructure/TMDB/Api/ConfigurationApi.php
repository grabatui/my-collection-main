<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB\Api;

use Tmdb\Api\AbstractApi;

class ConfigurationApi extends AbstractApi
{
    /**
     * @param array<string, string> $headers
     *
     * @return array<int, mixed>
     */
    public function getCountries(array $headers = []): array
    {
        return $this->get('configuration/countries', [], $headers);
    }

    /**
     * @param array<string, string> $headers
     *
     * @return array<int, mixed>
     */
    public function getJobs(array $headers = []): array
    {
        return $this->get('configuration/jobs', [], $headers);
    }
}
