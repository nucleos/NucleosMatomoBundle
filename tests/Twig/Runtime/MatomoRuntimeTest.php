<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\Twig\Runtime;

use Nucleos\MatomoBundle\Twig\Runtime\MatomoRuntime;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Twig\Environment;

final class MatomoRuntimeTest extends TestCase
{
    /**
     * @var Environment&MockObject
     */
    private Environment $environment;

    private MatomoRuntime $runtime;

    protected function setUp(): void
    {
        $this->environment = $this->createMock(Environment::class);
        $this->runtime     = new MatomoRuntime();
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

        self::assertSame('HTML CONTENT', $this->runtime->renderTracker($this->environment, [
            'site_id' => 13,
        ]));
    }

    public function testRenderTrackerWithoutSiteId(): void
    {
        $this->environment->expects(self::never())->method('render');

        self::assertSame('', $this->runtime->renderTracker($this->environment));
    }
}
