<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\Twig\Extension;

use Core23\MatomoBundle\Twig\Extension\MatomoTwigExtension;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Twig\Environment;
use Twig\TwigFunction;

final class MatomoTwigExtensionTest extends TestCase
{
    private $environment;

    protected function setUp(): void
    {
        $this->environment = $this->prophesize(Environment::class);
    }

    public function testGetFunctions(): void
    {
        $extension = new MatomoTwigExtension($this->environment->reveal());

        $functions = $extension->getFunctions();

        static::assertCount(1, $functions);

        foreach ($functions as $function) {
            static::assertInstanceOf(TwigFunction::class, $function);
            static::assertIsCallable($function->getCallable());
        }
    }

    public function testRenderTracker(): void
    {
        $this->environment->render('@Core23Matomo/tracker_code.html.twig', [
            'site_id'       => 13,
            'matomo_host'   => 'localhost',
            'cookie_domain' => null,
        ])
            ->willReturn('HTML CONTENT')
        ;

        $extension = new MatomoTwigExtension($this->environment->reveal());

        static::assertSame('HTML CONTENT', $extension->renderTracker([
            'site_id' => 13,
        ]));
    }

    public function testRenderTrackerWithoutSiteId(): void
    {
        $extension = new MatomoTwigExtension($this->environment->reveal());

        $this->environment->render(Argument::any())
            ->shouldNotBeCalled()
        ;

        static::assertSame('', $extension->renderTracker());
    }
}
