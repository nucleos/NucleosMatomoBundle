<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nucleos\MatomoBundle\Twig\Runtime;

use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

final class MatomoRuntime implements RuntimeExtensionInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function renderTracker(Environment $environment, array $options = []): string
    {
        $data = array_merge([
            'site_id'       => null,
            'matomo_host'   => 'localhost',
            'cookie_domain' => null,
        ], $options);

        if (null === $data['site_id']) {
            return '';
        }

        return $environment->render('@NucleosMatomo/tracker_code.html.twig', $data);
    }
}
