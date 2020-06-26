<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests;

use Nucleos\MatomoBundle\DependencyInjection\NucleosMatomoExtension;
use Nucleos\MatomoBundle\NucleosMatomoBundle;
use PHPUnit\Framework\TestCase;

final class NucleosMatomoBundleTest extends TestCase
{
    public function testGetContainerExtension(): void
    {
        $bundle = new NucleosMatomoBundle();

        static::assertInstanceOf(NucleosMatomoExtension::class, $bundle->getContainerExtension());
    }
}
