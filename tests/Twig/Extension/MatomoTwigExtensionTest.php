<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\Twig\Extension;

use Nucleos\MatomoBundle\Twig\Extension\MatomoTwigExtension;
use PHPUnit\Framework\TestCase;
use Twig\TwigFunction;

final class MatomoTwigExtensionTest extends TestCase
{
    public function testGetFunctions(): void
    {
        $extension = new MatomoTwigExtension();

        $functions = $extension->getFunctions();

        self::assertCount(1, $functions);

        foreach ($functions as $function) {
            self::assertInstanceOf(TwigFunction::class, $function);
            self::assertIsArray($callable = $function->getCallable());
            self::assertTrue(method_exists($callable[0], $callable[1]));
        }
    }
}
