<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Client;

use Nucleos\MatomoBundle\Connection\ConnectionInterface;
use Nucleos\MatomoBundle\Exception\MatomoException;

interface ClientInterface
{
    /**
     * Set Matomo API token.
     *
     * @param string $token auth token
     *
     * @deprecated use constructor to set client token
     */
    public function setToken(string $token): void;

    /**
     * Call specific method & return it's response.
     *
     * @param string               $method method name
     * @param array<string, mixed> $params method parameters
     *
     * @throws MatomoException
     *
     * @return array<mixed>|string
     */
    public function call(string $method, array $params = [], string $format = 'php');

    /**
     * Return active connection.
     *
     * @deprecated without any replacement
     */
    public function getConnection(): ConnectionInterface;
}
