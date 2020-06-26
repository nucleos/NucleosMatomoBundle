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
use Nucleos\MatomoBundle\Connection\ConnectionInterface;
use Nucleos\MatomoBundle\Exception\MatomoException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

final class ClientTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy
     */
    private $connection;

    protected function setUp(): void
    {
        $this->connection = $this->prophesize(ConnectionInterface::class);
    }

    public function testGetConnection(): void
    {
        $client = new Client($this->connection->reveal());

        static::assertSame($this->connection->reveal(), $client->getConnection());
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

        static::assertSame(['result' => 'data'], $result);
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

        static::assertSame('The result', $result);
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
