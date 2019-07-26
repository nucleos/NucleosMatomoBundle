<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Client;

use Core23\MatomoBundle\Connection\HttplugConnection;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class HttplugClientFactory implements ClientFactoryInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * Initialize client.
     */
    public function __construct(HttpClient $client, MessageFactory $messageFactory)
    {
        $this->client         = $client;
        $this->messageFactory = $messageFactory;
    }

    public function createClient(string $host, string $token): ClientInterface
    {
        $connection = new HttplugConnection($this->client, $this->messageFactory, $host);

        return new Client($connection, $token);
    }
}
