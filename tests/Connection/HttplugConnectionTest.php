<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Connection;

use Core23\MatomoBundle\Connection\HttplugConnection;
use Core23\MatomoBundle\Exception\MatomoException;
use Core23\MatomoBundle\Tests\Fixtures\ClientException;
use DateTime;
use Exception;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class HttplugConnectionTest extends TestCase
{
    private $client;

    private $messageFactory;

    protected function setUp(): void
    {
        $this->client         = $this->prophesize(HttpClient::class);
        $this->messageFactory = $this->prophesize(MessageFactory::class);
    }

    public function testSend(): void
    {
        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
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
        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?date=2010-02-10&module=API')
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
        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?active=1&module=API')
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
        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?foo=bar,baz&module=API')
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

        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
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

        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $this->client->sendRequest($request)
            ->willThrow(ClientException::class)
        ;

        $client->send(['foo' => 'bar']);
    }

    public function testSendWithInvalidResponse(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('"http://api.url?foo=bar&module=API" returned an invalid status code: "500"');

        $client = new HttplugConnection($this->client->reveal(), $this->messageFactory->reveal(), 'http://api.url');

        $request =  $this->prophesize(RequestInterface::class);

        $this->messageFactory->createRequest('GET', 'http://api.url?foo=bar&module=API')
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
