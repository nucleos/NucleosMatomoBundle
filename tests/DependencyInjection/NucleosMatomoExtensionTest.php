<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Tests\DependencyInjection;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Nucleos\MatomoBundle\Block\Service\MatomoStatisticBlockService;
use Nucleos\MatomoBundle\Block\Service\MatomoTrackerBlockService;
use Nucleos\MatomoBundle\DependencyInjection\NucleosMatomoExtension;
use Nucleos\MatomoBundle\Twig\Extension\MatomoTwigExtension;

final class NucleosMatomoExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load([
            'http' => [
                'client'          => 'acme.client',
                'message_factory' => 'acme.message_factory',
            ],
        ]);

        $this->assertContainerBuilderHasService(MatomoTwigExtension::class);

        $this->assertContainerBuilderHasAlias('nucleos_matomo.http.client', 'acme.client');
        $this->assertContainerBuilderHasAlias('nucleos_matomo.http.message_factory', 'acme.message_factory');
    }

    public function testLoadWithBlockBundle(): void
    {
        $this->setParameter('kernel.bundles', ['SonataBlockBundle' => true]);
        $this->load([
            'http' => [
                'client'          => 'acme.client',
                'message_factory' => 'acme.message_factory',
            ],
        ]);

        $this->assertContainerBuilderHasService('nucleos_matomo.block.statistic', MatomoStatisticBlockService::class);
        $this->assertContainerBuilderHasService('nucleos_matomo.block.tracker', MatomoTrackerBlockService::class);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new NucleosMatomoExtension(),
        ];
    }
}
