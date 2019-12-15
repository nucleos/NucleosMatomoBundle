<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Core23\MatomoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('core23_matomo');

        $rootNode = $treeBuilder->getRootNode();

        \assert($rootNode instanceof ArrayNodeDefinition);

        $this->addHttpClientSection($rootNode);

        return $treeBuilder;
    }

    private function addHttpClientSection(ArrayNodeDefinition $node): void
    {
        $node
            ->children()
                ->arrayNode('http')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('client')->defaultNull()->end()
                        ->scalarNode('message_factory')->defaultNull()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
