<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Twig\Extension;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class MatomoTwigExtension extends AbstractExtension
{
    /**
     * @var Environment
     */
    private $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('matomo_tracker', [$this, 'renderTracker'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @param array<string, mixed> $options
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function renderTracker(array $options = []): string
    {
        $data = array_merge([
            'site_id'       => null,
            'matomo_host'   => 'localhost',
            'cookie_domain' => null,
        ], $options);

        if (null === $data['site_id']) {
            return '';
        }

        return $this->environment->render('@Core23Matomo/tracker_code.html.twig', $data);
    }
}
