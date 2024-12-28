<?php

declare(strict_types=1);

namespace App\Domain\Service\Mail\Dto;

class HtmlTemplate
{
    /**
     * @param array<string, mixed> $variables
     */
    public function __construct(
        public string $template,
        public array $variables,
    ) {}
}
