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
                ->enumNode('driver')
                    ->defaultValue('doctrine')
                    ->values(['doctrine'])
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}