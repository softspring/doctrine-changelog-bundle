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

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));

        $loader->load('doctrine_changes_listener.yaml');
        $loader->load('commands.yaml');

        foreach (array_keys(array_filter($config['collect'])) as $collector) {
            $loader->load("collector/{$collector}.yaml");
        }
        $loader->load('collector/changes.yaml');

        if ($config['storage']['enabled']) {
            $loader->load('storage.yaml');
            $loader->load("storage_driver/{$config['storage']['driver']}.yaml");

            $container->setParameter('sfs_doctrine_changelog.storage.driver', $config['storage']['driver']);
            $container->setParameter('sfs_doctrine_changelog.storage.options', $config['storage']['options']);
        }

        if ($config['storage']['driver'] == 'big-query') {
            // default bigQuery fields
            $bigQueryFields = [
                ['name' => 'id',             'type' => 'INTEGER',  'mode' => 'REQUIRED'],
                ['name' => 'timestamp',      'type' => 'INTEGER',  'mode' => 'REQUIRED'],
                ['name' => 'entity_class',   'type' => 'STRING',   'mode' => ''],
                ['name' => 'entity_id',      'type' => 'STRING',   'mode' => ''],
                ['name' => 'changes',        'type' => 'STRING',   'mode' => ''],

                // TODO check if user collector is present
                ['name' => 'username',       'type' => 'STRING',   'mode' => ''],

                // TODO check if request collector is present
                ['name' => 'request_ip',     'type' => 'STRING',   'mode' => ''],
                ['name' => 'user_agent',     'type' => 'STRING',   'mode' => ''],
                ['name' => 'request_method', 'type' => 'STRING',   'mode' => ''],
                ['name' => 'request_path',   'type' => 'STRING',   'mode' => ''],

                // TODO check if action collector is present
                ['name' => 'action',         'type' => 'STRING',   'mode' => ''],
            ];

            if (is_array($config['storage']['options']['schema']['extra_fields'])) {
                $bigQueryFields = array_merge($bigQueryFields, $config['storage']['options']['schema']['extra_fields']);
            }

            $container->setParameter('sfs_doctrine_changelog.storage.big_query.schema', [
                'fields' => $bigQueryFields,
            ]);
        }
    }
}