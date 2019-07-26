<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Connection;

use Core23\MatomoBundle\Exception\MatomoException;
use DateTime;
use Exception;
use Http\Client\Exception as ClientException;
use Http\Client\HttpClient;
use Http\Message\MessageFactory;

final class HttplugConnection implements ConnectionInterface
{
    /**
     * @var HttpClient
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * Initialize client.
     *
     * @param string $apiUrl base API URL
     */
    public function __construct(HttpClient $client, MessageFactory $messageFactory, string $apiUrl)
    {
        $this->apiUrl         = $apiUrl;
        $this->client         = $client;
        $this->messageFactory = $messageFactory;
    }

    public function send(array $params = []): string
    {
        $params['module'] = 'API';

        $url      = $this->apiUrl.'?'.$this->getUrlParamString($params);
        $request  = $this->messageFactory->createRequest('GET', $url);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientException $exception) {
            throw new MatomoException('Error calling Matomo API.', $exception->getCode(), $exception);
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
