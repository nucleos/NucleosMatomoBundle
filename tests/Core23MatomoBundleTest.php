<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests;

use Core23\MatomoBundle\Core23MatomoBundle;
use Core23\MatomoBundle\DependencyInjection\Core23MatomoExtension;
use PHPUnit\Framework\TestCase;

final class Core23MatomoBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new Core23MatomoBundle();

        static::assertInstanceOf(Core23MatomoExtension::class, $bundle->getContainerExtension());
    }
}
