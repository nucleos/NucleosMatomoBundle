<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Client;

use Core23\MatomoBundle\Client\ClientFactoryInterface;
use Core23\MatomoBundle\Client\ClientInterface;
use Core23\MatomoBundle\Client\HttplugClientFactory;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use PHPUnit\Framework\TestCase;

final class HttplugClientFactoryTest extends TestCase
{
    private $client;

    private $messageFactory;

    protected function setUp()
    {
        $this->client         = $this->prophesize(HttpClient::class);
        $this->messageFactory = $this->prophesize(MessageFactory::class);
    }

    public function testItIsInstantiable(): void
    {
        $factory = new HttplugClientFactory($this->client->reveal(), $this->messageFactory->reveal());

        static::assertInstanceOf(ClientFactoryInterface::class, $factory);
    }

    public function testGetConnection(): void
    {
        $factory = new HttplugClientFactory($this->client->reveal(), $this->messageFactory->reveal());

        static::assertInstanceOf(ClientInterface::class, $factory->createClient('http://localhost', 'MY_TOKEN'));
    }
}
