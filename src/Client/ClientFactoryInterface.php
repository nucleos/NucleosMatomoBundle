<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Client;

interface ClientFactoryInterface
{
    /**
     * Creates new Matomo Client.
     *
     * @param string $host
     * @param string $token
     *
     * @return ClientInterface
     */
    public function createClient(string $host, string $token): ClientInterface;
}
