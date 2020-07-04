<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\MatomoBundle\Block\Service\MatomoStatisticBlockService;
use Nucleos\MatomoBundle\Block\Service\MatomoTrackerBlockService;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('nucleos_matomo.block.statistic', MatomoStatisticBlockService::class)
            ->tag('sonata.block')
            ->args([
                new Reference('twig'),
                new Reference('nucleos_matomo.client.factory'),
            ])
            ->call('setLogger', [
                new Reference('logger'),
            ])

        ->set('nucleos_matomo.block.tracker', MatomoTrackerBlockService::class)
            ->tag('sonata.block')
            ->args([
                new Reference('twig'),
            ])
    ;
};
