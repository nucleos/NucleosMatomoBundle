<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests;

use Core23\MatomoBundle\Core23MatomoBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class Core23MatomoBundleTest extends TestCase
{
    public function testItIsInstantiable(): void
    {
        $bundle = new Core23MatomoBundle();

        $this->assertInstanceOf(BundleInterface::class, $bundle);
    }
}
