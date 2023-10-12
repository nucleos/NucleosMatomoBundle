<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Twig\Extension;

use Nucleos\MatomoBundle\Twig\Runtime\MatomoRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MatomoTwigExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('matomo_tracker', [MatomoRuntime::class, 'renderTracker'], [
                'needs_environment' => true,
                'is_safe'           => ['html'],
            ]),
        ];
    }
}
