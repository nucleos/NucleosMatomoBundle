<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\Connection;

use DateTime;
use Exception;
use Nucleos\MatomoBundle\Connection\PsrClientConnection;
use Nucleos\MatomoBundle\Exception\MatomoException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

final class PsrClientConnectionTest extends TestCase
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

    public function testSend(): void
    {
        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->method('sendRequest')->with($request)
            ->willReturn($response)
        ;

        self::assertSame('my content', $client->send(['foo' => 'bar']));
    }

    public function testSendWithDateParameter(): void
    {
        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?date=2010-02-10&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->method('sendRequest')->with($request)
            ->willReturn($response)
        ;

        self::assertSame('my content', $client->send(['date' => new DateTime('2010-02-10')]));
    }

    public function testSendWithBooleanParameter(): void
    {
        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?active=1&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->method('sendRequest')->with($request)
            ->willReturn($response)
        ;

        self::assertSame('my content', $client->send(['active' => true, 'inactive' => false]));
    }

    public function testSendWithArrayParameter(): void
    {
        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?foo=bar,baz&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('my content');

        $this->client->method('sendRequest')->with($request)
            ->willReturn($response)
        ;

        self::assertSame('my content', $client->send(['foo' => ['bar', 'baz']]));
    }

    public function testSendWithException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Error calling Matomo API.');

        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $this->client->method('sendRequest')->with($request)
            ->willThrowException(new Exception())
        ;

        $client->send(['foo' => 'bar']);
    }

    public function testSendWithClientException(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('Error calling Matomo API.');

        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $this->client->method('sendRequest')->with($request)
            ->willThrowException(new class() extends Exception implements ClientExceptionInterface {})
        ;

        $client->send(['foo' => 'bar']);
    }

    public function testSendWithInvalidResponse(): void
    {
        $this->expectException(MatomoException::class);
        $this->expectExceptionMessage('"http://api.url?foo=bar&module=API" returned an invalid status code: "500"');

        $client = new PsrClientConnection($this->client, $this->requestFactory, 'http://api.url');

        $request =  $this->createMock(RequestInterface::class);

        $this->requestFactory->method('createRequest')->with('GET', 'http://api.url?foo=bar&module=API')
            ->willReturn($request)
        ;

        $response =$this->prepareResponse('', 500);

        $this->client->method('sendRequest')->with($request)
            ->willReturn($response)
        ;

        $client->send(['foo' => 'bar']);
    }

    /**
     * @return MockObject&ResponseInterface
     */
    private function prepareResponse(string $content, int $code = 200): ResponseInterface
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')
            ->willReturn($content)
        ;

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getBody')
            ->willReturn($stream)
        ;
        $response->method('getStatusCode')
            ->willReturn($code)
        ;

        return $response;
    }
}
