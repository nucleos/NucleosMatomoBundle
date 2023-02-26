<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Connection;

use Nucleos\MatomoBundle\Exception\MatomoException;

interface ConnectionInterface
{
    /**
     * Calls specific method on Matomo API.
     *
     * @param array<string, mixed> $params
     *
     * @return string response
     *
     * @throws MatomoException
     */
    public function send(array $params = []): string;
}
