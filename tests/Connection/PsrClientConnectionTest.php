<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Connection;

use Core23\MatomoBundle\Connection\PsrClientConnection;
use Core23\MatomoBundle\Exception\MatomoException;
use DateTime;
use Exception;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class PsrClientConnectionTest extends TestCase
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

    public function testSend(): void
    {
        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->sendRequest($request)
            ->willReturn($response)
        ;

        static::assertSame('my content', $client->send(['foo' => 'bar']));
    }

    public function testSendWithDateParameter(): void
    {
        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?date=2010-02-10&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->sendRequest($request)
            ->willReturn($response)
        ;

        static::assertSame('my content', $client->send(['date' => new DateTime('2010-02-10')]));
    }

    public function testSendWithBooleanParameter(): void
    {
        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?active=1&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->sendRequest($request)
            ->willReturn($response)
        ;

        static::assertSame('my content', $client->send(['active' => true, 'inactive' => false]));
    }

    public function testSendWithArrayParameter(): void
    {
        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?foo=bar,baz&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->sendRequest($request)
            ->willReturn($response)
        ;

        static::assertSame('my content', $client->send(['foo' => ['bar', 'baz']]));
    }

    public function testSendWithException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error calling Matomo API.');

        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $this->client->sendRequest($request)
            ->willThrow(Exception::class)
        ;

        $client->send(['foo' => 'bar']);
    }

    public function testSendWithClientException(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('Error calling Matomo API.');

        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $this->client->sendRequest($request)
            ->willThrow(new class() extends Exception implements ClientExceptionInterface {
            })
        ;

        $client->send(['foo' => 'bar']);
    }

    public function testSendWithInvalidResponse(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('"http://api.url?foo=bar&module=API" returned an invalid status code: "500"');

        $client = new PsrClientConnection($this->client->reveal(), $this->requestFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->requestFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('', 500);

        $this->client->sendRequest($request)
            ->willReturn($response)
        ;

        $client->send(['foo' => 'bar']);
    }

    private function prepareResponse(string $content, int $code = 200): ObjectProphecy
    {
        $stream = $this->prophesize(StreamInterface::class);
        $stream->getContents()
            ->willReturn($content)
        ;

        $response = $this->prophesize(ResponseInterface::class);
        $response->getBody()
            ->willReturn($stream)
        ;
        $response->getStatusCode()
            ->willReturn($code)
        ;

        return $response;
    }
}
