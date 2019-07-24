<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Client;

use Core23\MatomoBundle\Connection\ConnectionInterface;
use Core23\MatomoBundle\Exception\MatomoException;

interface ClientInterface
{
    /**
     * Set Matomo API token.
     *
     * @param string $token auth token
     *
     * @deprecated use constructor to set client token
     */
    public function setToken(string $token);

    /**
     * Call specific method & return it's response.
     *
     * @param string $method method name
     * @param array  $params method parameters
     * @param string $format return format (php, json, xml, csv, tsv, html, rss)
     *
     * @throws MatomoException
     */
    public function call(string $method, array $params = [], string $format = 'php');

    /**
     * Return active connection.
     *
     * @deprecated without any replacement
     */
    public function getConnection(): ConnectionInterface;
}
