<?php

declare(strict_types=1);

namespace App\Infrastructure\TMDB;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Tmdb\Client;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\Listener\RequestListener;
use Tmdb\Event\RequestEvent;
use Tmdb\Token\Api\BearerToken;

readonly class ClientFactory
{
    public function __construct(
        private string $apiToken,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function create(): Client
    {
        $client = new Client([
            'api_token' => new BearerToken($this->apiToken),
            'event_dispatcher' => [
                'adapter' => $this->eventDispatcher,
            ],
        ]);

        $this->addBeforeRequestListener(new ApiTokenRequestListener($client->getToken()));
        $this->addBeforeRequestListener(new AcceptJsonRequestListener());
        $this->addBeforeRequestListener(new ContentTypeJsonRequestListener());
        $this->addBeforeRequestListener(new UserAgentRequestListener());

        $this->addRequestListener(new RequestListener($client->getHttpClient(), $this->eventDispatcher));

        return $client;
    }

    private function addBeforeRequestListener(callable $listener): void
    {
        $this->eventDispatcher->addListener(BeforeRequestEvent::class, $listener);
    }

    private function addRequestListener(callable $listener): void
    {
        $this->eventDispatcher->addListener(RequestEvent::class, $listener);
    }
}
