<?php

namespace Softspring\DoctrineChangeLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
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
                        ->arrayNode('options')
                            ->variablePrototype()
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}