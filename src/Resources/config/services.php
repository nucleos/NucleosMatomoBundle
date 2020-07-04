<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\MatomoBundle\Client\ClientFactoryInterface;
use Nucleos\MatomoBundle\Client\PsrClientFactory;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set('nucleos_matomo.client.factory', PsrClientFactory::class)
            ->args([
                new Reference('nucleos_matomo.http.client'),
                new Reference('nucleos_matomo.http.message_factory'),
            ])

        ->alias(ClientFactoryInterface::class, 'nucleos_matomo.client.factory')
    ;
};
