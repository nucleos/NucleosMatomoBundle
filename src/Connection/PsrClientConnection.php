<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Connection;

use DateTime;
use Exception;
use Nucleos\MatomoBundle\Exception\MatomoException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestFactoryInterface;

final class PsrClientConnection implements ConnectionInterface
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
     * @var string
     */
    private $apiUrl;

    /**
     * Initialize client.
     *
     * @param string $apiUrl base API URL
     */
    public function __construct(PsrClientInterface $client, RequestFactoryInterface $requestFactory, string $apiUrl)
    {
        $this->apiUrl         = $apiUrl;
        $this->client         = $client;
        $this->requestFactory = $requestFactory;
    }

    public function send(array $params = []): string
    {
        $params['module'] = 'API';

        $url      = $this->apiUrl.'?'.$this->getUrlParamString($params);
        $request  = $this->requestFactory->createRequest('GET', $url);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $exception) {
            throw new MatomoException('Error calling Matomo API.', 500, $exception);
        } catch (Exception $exception) {
            throw new MatomoException('Error calling Matomo API.', 500, $exception);
        }

        if (200 !== $response->getStatusCode()) {
            throw new MatomoException(sprintf('"%s" returned an invalid status code: "%s"', $url, $response->getStatusCode()));
        }

        return $response->getBody()->getContents();
    }

    /**
     * Converts a set of parameters to a query string.
     *
     * @param array<string, mixed> $params
     *
     * @return string query string
     */
    private function getUrlParamString(array $params): string
    {
        $query = [];
        foreach ($params as $key => $val) {
            if (\is_array($val)) {
                $val = implode(',', $val);
            } elseif ($val instanceof DateTime) {
                $val = $val->format('Y-m-d');
            } elseif (\is_bool($val)) {
                if ($val) {
                    $val = 1;
                } else {
                    continue;
                }
            } else {
                $val = urlencode((string) $val);
            }
            $query[] = $key.'='.$val;
        }

        return implode('&', $query);
    }
}
