<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Client;

use Exception;
use Nucleos\MatomoBundle\Connection\ConnectionInterface;
use Nucleos\MatomoBundle\Exception\MatomoException;

final class Client implements ClientInterface
{
    private ConnectionInterface $connection;

    private string $token;

    /**
     * Initialize Matomo client.
     *
     * @param ConnectionInterface $connection Matomo active connection
     * @param string              $token      auth token
     */
    public function __construct(ConnectionInterface $connection, string $token = 'anonymous')
    {
        $this->connection = $connection;
        $this->setToken($token);
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function call(string $method, array $params = [], string $format = 'php')
    {
        $params['method']     = $method;
        $params['token_auth'] = $this->token;
        $params['format']     = 'json';
        $data                 = $this->getConnection()->send($params);

        if ('php' !== $format) {
            @trigger_error(sprintf('Argument #3 of %s is deprecated and will be removed in 4.0.', __METHOD__), E_USER_DEPRECATED);
        }

        try {
            $object = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            throw new MatomoException('Invalid server response');
        }

        return $object;
    }

    public function getConnection(): ConnectionInterface
    {
        return $this->connection;
    }
}
