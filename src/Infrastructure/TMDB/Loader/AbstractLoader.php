<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB\Loader;

use Psr\EventDispatcher\EventDispatcherInterface;
use Tmdb\Event\Listener\Request\LanguageFilterRequestListener;
use Tmdb\Event\RequestEvent;

abstract readonly class AbstractLoader
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    protected function setRequestLanguage(string $language = 'ru-RU'): void
    {
        $this->eventDispatcher->addListener(RequestEvent::class, new LanguageFilterRequestListener($language), 1000);
    }
}
