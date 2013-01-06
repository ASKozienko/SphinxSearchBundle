<?php

namespace ASK\SphinxSearchBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ask_sphinx_search');

        $rootNode
            ->children()
                ->arrayNode('indexes')
                    ->useAttributeAsKey('alias')
                    ->prototype('scalar')->cannotBeEmpty()->end()
                ->end()
                ->scalarNode('connect_timeout')->defaultValue(0)->end()
                ->scalarNode('retry_delay')->defaultValue(0)->end()
                ->scalarNode('retry_count')->defaultValue(0)->end()
                ->scalarNode('port')->defaultValue('9312')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->booleanNode('debug')->defaultValue(false)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
