<?php

namespace Softspring\DoctrineChangeLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SfsDoctrineChangeLogExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $config = $processor->processConfiguration($configuration, $configs);

        $driver = $config['driver'];

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));

        $loader->load('doctrine_changes_listener.yaml');
        $loader->load('collector_listeners.yaml');

        if ($driver === 'doctrine') {
            $loader->load('storage_driver/doctrine.yaml');
            $loader->load('storage.yaml');
        }
    }
}