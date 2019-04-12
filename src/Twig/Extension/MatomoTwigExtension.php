<?php

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

    /**
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('matomo_tracker', [$this, 'renderTracker'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    /**
     * @param array $options
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return string
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
