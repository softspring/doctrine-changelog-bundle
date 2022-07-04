<?php

namespace Softspring\DoctrineChangeLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sfs_doctrine_change_log');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()

                ->arrayNode('collect')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('request')->defaultTrue()->end()
                        ->booleanNode('user')->defaultTrue()->end()
                        ->booleanNode('action')->defaultTrue()->end()
                    ->end()
                ->end()

                ->arrayNode('storage')
                    ->addDefaultsIfNotSet()
                    ->canBeEnabled()
                    ->children()
                        ->enumNode('driver')
                            ->values(['doctrine', 'big-query'])
                            ->isRequired()
                        ->end()

                        ->arrayNode('big_query')
                            ->children()
                                ->scalarNode('project')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('keyFilePath')
                                ->end()
                                ->scalarNode('dataset')
                                    ->isRequired()
                                ->end()
                                ->scalarNode('location')
                                    ->defaultValue('EU')
                                ->end()

                                ->arrayNode('table')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->enumNode('mode')
                                            ->isRequired()
                                            ->defaultValue('fixed')
                                            ->values(['fixed', 'attribute', 'service'])
                                        ->end()

                                        ->scalarNode('name')
                                            ->defaultNull()
                                        ->end()

                                        ->arrayNode('fixed')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('name')->isRequired()->defaultValue('changelog')->end()
                                            ->end()
                                        ->end()

                                        ->arrayNode('attribute')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('prefix')->isRequired()->defaultValue('changelog_')->end()
                                                ->scalarNode('attribute_name')->isRequired()->end()
                                            ->end()
                                        ->end()

                                        ->scalarNode('service')->end()
                                    ->end()
                                ->end()

                                ->arrayNode('schema')
                                    ->children()
                                        ->arrayNode('extra_fields')
                                            ->arrayPrototype()
                                                ->children()
                                                    ->scalarNode('name')->end()
                                                    ->enumNode('type')->values(['string', 'integer', 'float', 'boolean', 'datetime', 'timestamp'])->end()
                                                    ->enumNode('mode')->values(['nullable', 'required', 'repeated'])->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
