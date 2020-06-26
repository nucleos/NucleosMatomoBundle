<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Client;

use Nucleos\MatomoBundle\Connection\PsrClientConnection;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class PsrClientFactory implements ClientFactoryInterface
{
    /**
     * @var PsrClientInterface
     */
    private $client;

    /**
     * @var RequestFactoryInterface
     */
    private $requestFactory;

    /**
     * Initialize client.
     */
    public function __construct(PsrClientInterface $client, RequestFactoryInterface $requestFactory)
    {
        $this->client         = $client;
        $this->requestFactory = $requestFactory;
    }

    public function createClient(string $host, string $token): ClientInterface
    {
        $connection = new PsrClientConnection($this->client, $this->requestFactory, $host);

        return new Client($connection, $token);
    }
}
