<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\DependencyInjection;

use Core23\MatomoBundle\Block\Service\MatomoStatisticBlockService;
use Core23\MatomoBundle\Block\Service\MatomoTrackerBlockService;
use Core23\MatomoBundle\DependencyInjection\Core23MatomoExtension;
use Core23\MatomoBundle\Twig\Extension\MatomoTwigExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class Core23MatomoExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderHasService(MatomoTwigExtension::class);

        $this->assertContainerBuilderHasAlias('core23_matomo.http.client', 'httplug.client.default');
        $this->assertContainerBuilderHasAlias('core23_matomo.http.message_factory', 'httplug.message_factory.default');
    }

    public function testLoadWithBlockBundle(): void
    {
        $this->setParameter('kernel.bundles', ['SonataBlockBundle' => true]);
        $this->load();

        $this->assertContainerBuilderHasService('core23_matomo.block.statistic', MatomoStatisticBlockService::class);
        $this->assertContainerBuilderHasService('core23_matomo.block.tracker', MatomoTrackerBlockService::class);
    }

    protected function getContainerExtensions()
    {
        return [
            new Core23MatomoExtension(),
        ];
    }
}
