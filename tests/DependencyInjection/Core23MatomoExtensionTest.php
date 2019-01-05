<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\DependencyInjection;

use Core23\MatomoBundle\DependencyInjection\Core23MatomoExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

final class Core23MatomoExtensionTest extends AbstractExtensionTestCase
{
    public function testLoadDefault(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load();

        $this->assertContainerBuilderHasAlias('core23_matomo.http.client', 'httplug.client.default');
        $this->assertContainerBuilderHasAlias('core23_matomo.http.message_factory', 'httplug.message_factory.default');
    }

    protected function getContainerExtensions()
    {
        return [
            new Core23MatomoExtension(),
        ];
    }
}
