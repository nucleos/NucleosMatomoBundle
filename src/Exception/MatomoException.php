<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Exception;

use Throwable;

final class MatomoException extends \Exception
{
    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        $message = 'Matomo API error: '.$message;

        parent::__construct($message, $code, $previous);
    }
}
