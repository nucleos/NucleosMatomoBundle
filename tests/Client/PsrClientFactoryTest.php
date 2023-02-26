<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\Client;

use Nucleos\MatomoBundle\Client\Client;
use Nucleos\MatomoBundle\Client\PsrClientFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class PsrClientFactoryTest extends TestCase
{
    /**
     * @var MockObject&PsrClientInterface
     */
    private PsrClientInterface $client;

    /**
     * @var MockObject&RequestFactoryInterface
     */
    private RequestFactoryInterface $requestFactory;

    protected function setUp(): void
    {
        $this->client         = $this->createMock(PsrClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
    }

    public function testGetConnection(): void
    {
        $factory = new PsrClientFactory($this->client, $this->requestFactory);

        $client = $factory->createClient('http://localhost', 'MY_TOKEN');

        static::assertInstanceOf(Client::class, $client);
    }
}
