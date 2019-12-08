<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\Tests\DependencyInjection;

use Core23\MatomoBundle\DependencyInjection\Configuration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

final class ConfigurationTest extends TestCase
{
    public function testOptions(): void
    {
        $processor = new Processor();

        $config = $processor->processConfiguration(new Configuration(), [[
        ]]);

        $expected = [
            'http' => [
                'client'          => null,
                'message_factory' => null,
            ],
        ];

        static::assertSame($expected, $config);
    }
}
