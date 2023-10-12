<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Nucleos\MatomoBundle\Twig\Extension\MatomoTwigExtension;
use Nucleos\MatomoBundle\Twig\Runtime\MatomoRuntime;

return static function (ContainerConfigurator $container): void {
    $container->services()

        ->set(MatomoTwigExtension::class)
            ->tag('twig.extension')

        ->set(MatomoRuntime::class)
            ->tag('twig.runtime')
    ;
};
