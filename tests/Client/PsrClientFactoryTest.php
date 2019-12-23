<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Client;

use Core23\MatomoBundle\Client\Client;
use Core23\MatomoBundle\Client\PsrClientFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class PsrClientFactoryTest extends TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $client;

    /**
     * @var ObjectProphecy
     */
    private $requestFactory;

    protected function setUp(): void
    {
        $this->client         = $this->prophesize(PsrClientInterface::class);
        $this->requestFactory = $this->prophesize(RequestFactoryInterface::class);
    }

    public function testGetConnection(): void
    {
        $factory = new PsrClientFactory($this->client->reveal(), $this->requestFactory->reveal());

        $client = $factory->createClient('http://localhost', 'MY_TOKEN');

        static::assertInstanceOf(Client::class, $client);
    }
}
