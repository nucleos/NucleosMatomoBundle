<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Client;

use Nucleos\MatomoBundle\Exception\MatomoException;

interface ClientInterface
{
    /**
     * Call specific method & return its response.
     *
     * @param string               $method method name
     * @param array<string, mixed> $params method parameters
     *
     * @return array<mixed>|string
     *
     * @throws MatomoException
     */
    public function call(string $method, array $params = []);
}
