<?php

namespace Zogs\WorldBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('zogs_world');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->arrayNode('exporter')
                    ->children()
                        ->scalarNode('file_name')->end()
                        ->scalarNode('export_dir')->end()
                    ->end()
                ->end()
                ->arrayNode('importer')
                    ->children()
                        ->booleanNode('load_fixtures')->end()
                        ->scalarNode('files_to_import')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
