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

interface ConnectionInterface
{
    /**
     * Calls specific method on Matomo API.
     *
     * @param array<string, mixed> $params
     *
     * @throws MatomoException
     *
     * @return string response
     */
    public function send(array $params = []): string;
}
