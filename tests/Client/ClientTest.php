<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Client;

use Core23\MatomoBundle\Client\Client;
use Core23\MatomoBundle\Client\ClientInterface;
use Core23\MatomoBundle\Connection\ConnectionInterface;
use Core23\MatomoBundle\Exception\MatomoException;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $connection;

    protected function setUp()
    {
        $this->connection = $this->prophesize(ConnectionInterface::class);
    }

    public function testItIsInstantiable(): void
    {
        $client = new Client($this->connection->reveal());

        $this->assertInstanceOf(ClientInterface::class, $client);
    }

    public function testGetConnection(): void
    {
        $client = new Client($this->connection->reveal());

        $this->assertSame($this->connection->reveal(), $client->getConnection());
    }

    public function testCall(): void
    {
        $this->connection->send([
            'foo'        => 'bar',
            'method'     => 'foo/method',
            'token_auth' => 'MY_TOKEN',
            'format'     => 'php',
        ])
        ->willReturn('a:1:{s:6:"result";s:4:"data";}')
        ;

        $client = new Client($this->connection->reveal(), 'MY_TOKEN');
        $result = $client->call('foo/method', ['foo' => 'bar']);

        $this->assertSame(['result' => 'data'], $result);
    }

    public function testCallWithCustomFormat(): void
    {
        $this->connection->send([
            'foo'        => 'bar',
            'method'     => 'foo/method',
            'token_auth' => 'MY_TOKEN',
            'format'     => 'custom',
        ])
        ->willReturn('The result')
        ;

        $client = new Client($this->connection->reveal(), 'MY_TOKEN');
        $result = $client->call('foo/method', ['foo' => 'bar'], 'custom');

        $this->assertSame('The result', $result);
    }

    public function testCallWithApiError(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('There is an error');

        $this->connection->send([
            'foo'        => 'bar',
            'method'     => 'foo/method',
            'token_auth' => 'MY_TOKEN',
            'format'     => 'php',
        ])
        ->willReturn('a:2:{s:6:"result";s:5:"error";s:7:"message";s:17:"There is an error";}')
        ;

        $client = new Client($this->connection->reveal(), 'MY_TOKEN');

        $client->call('foo/method', ['foo' => 'bar']);
    }
}