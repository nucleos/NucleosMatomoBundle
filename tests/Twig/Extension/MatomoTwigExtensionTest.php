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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\TwigFunction;

final class MatomoTwigExtensionTest extends TestCase
{
    /**
     * @var Environment&MockObject
     */
    private Environment $environment;

    protected function setUp(): void
    {
        $this->environment = $this->createMock(Environment::class);
    }

    public function testGetFunctions(): void
    {
        $extension = new MatomoTwigExtension($this->environment);

        $functions = $extension->getFunctions();

        static::assertCount(1, $functions);

        foreach ($functions as $function) {
            static::assertInstanceOf(TwigFunction::class, $function);
            static::assertIsCallable($function->getCallable());
        }
    }

    public function testRenderTracker(): void
    {
        $this->environment->method('render')->with('@NucleosMatomo/tracker_code.html.twig', [
            'site_id'       => 13,
            'matomo_host'   => 'localhost',
            'cookie_domain' => null,
        ])
            ->willReturn('HTML CONTENT')
        ;

        $extension = new MatomoTwigExtension($this->environment);

        static::assertSame('HTML CONTENT', $extension->renderTracker([
            'site_id' => 13,
        ]));
    }

    public function testRenderTrackerWithoutSiteId(): void
    {
        $extension = new MatomoTwigExtension($this->environment);

        $this->environment->expects(static::never())->method('render');

        static::assertSame('', $extension->renderTracker());
    }
}
