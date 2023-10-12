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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    /**
     * @var ConnectionInterface&MockObject
     */
    private ConnectionInterface $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(ConnectionInterface::class);
    }

    public function testCall(): void
    {
        $this->connection
            ->method('send')->with([
                'foo'        => 'bar',
                'method'     => 'foo/method',
                'token_auth' => 'MY_TOKEN',
                'format'     => 'json',
            ])
            ->willReturn('{"2020-11-07":1,"2020-11-08":2,"2020-11-09":6,"2020-11-10":6,"2020-11-11":8,"2020-11-12":6,"2020-11-13":2,"2020-11-14":1,"2020-11-15":3,"2020-11-16":4,"2020-11-17":13,"2020-11-18":4,"2020-11-19":5,"2020-11-20":3,"2020-11-21":5,"2020-11-22":5,"2020-11-23":6,"2020-11-24":11,"2020-11-25":4,"2020-11-26":9,"2020-11-27":4,"2020-11-28":4,"2020-11-29":4,"2020-11-30":3,"2020-12-01":12,"2020-12-02":7,"2020-12-03":6,"2020-12-04":3,"2020-12-05":4,"2020-12-06":1}')
        ;

        $client = new Client($this->connection, 'MY_TOKEN');
        $result = $client->call('foo/method', ['foo' => 'bar']);

        static::assertCount(30, $result);
    }

    /**
     * @expectedDeprecation Argument #3 of Nucleos\MatomoBundle\Client\Client::call is deprecated and will be removed in 4.0.
     *
     * @group legacy
     */
    public function testCallWithCustomFormat(): void
    {
        $this->connection
            ->method('send')->with([
                'foo'        => 'bar',
                'method'     => 'foo/method',
                'token_auth' => 'MY_TOKEN',
                'format'     => 'json',
            ])
            ->willReturn('{"2020-11-07":1,"2020-11-08":2,"2020-11-09":6,"2020-11-10":6,"2020-11-11":8,"2020-11-12":6,"2020-11-13":2,"2020-11-14":1,"2020-11-15":3,"2020-11-16":4,"2020-11-17":13,"2020-11-18":4,"2020-11-19":5,"2020-11-20":3,"2020-11-21":5,"2020-11-22":5,"2020-11-23":6,"2020-11-24":11,"2020-11-25":4,"2020-11-26":9,"2020-11-27":4,"2020-11-28":4,"2020-11-29":4,"2020-11-30":3,"2020-12-01":12,"2020-12-02":7,"2020-12-03":6,"2020-12-04":3,"2020-12-05":4,"2020-12-06":1}')
        ;

        $client = new Client($this->connection, 'MY_TOKEN');
        $result = $client->call('foo/method', ['foo' => 'bar']);

        static::assertCount(30, $result);
    }

    public function testCallWithApiError(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('Invalid server response');

        $this->connection
            ->method('send')->with([
                'foo'        => 'bar',
                'method'     => 'foo/method',
                'token_auth' => 'MY_TOKEN',
                'format'     => 'json',
            ])
            ->willReturn('')
        ;

        $client = new Client($this->connection, 'MY_TOKEN');

        $client->call('foo/method', ['foo' => 'bar']);
    }
}
