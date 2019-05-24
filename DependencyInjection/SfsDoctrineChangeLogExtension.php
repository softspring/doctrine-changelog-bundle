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

        foreach (array_keys(array_filter($config['collect'])) as $collector) {
            $loader->load("collector/{$collector}.yaml");
        }
        $loader->load('collector/changes.yaml');

        if ($config['storage']['enabled']) {
            $loader->load('storage.yaml');
            $loader->load("storage_driver/{$config['storage']['driver']}.yaml");

            $container->setParameter('sfs_doctrine_changelog.storage.driver', $config['storage']['driver']);

            $this->processBigQuery($config, $container);
        }
    }

    private function processBigQuery(array $config, ContainerBuilder $container)
    {
        if ($config['storage']['driver'] != 'big-query') {
            return;
        }

        $bigQueryOptions = [];

        // SET GLOBAL OPTIONS

        $bigQueryOptions['project'] = $config['storage']['big_query']['project'];
        $bigQueryOptions['dataset'] = $config['storage']['big_query']['dataset'];
        $bigQueryOptions['location'] = $config['storage']['big_query']['location'];

        // SET TABLE OPTIONS

        $bigQueryOptions['table']['mode'] = $config['storage']['big_query']['table']['mode'];

        if ($bigQueryOptions['table']['mode'] == 'fixed') {
            $bigQueryOptions['table']['name'] = $config['storage']['big_query']['table']['fixed']['name'];
        }

        if ($bigQueryOptions['table']['mode'] == 'attribute') {
            $bigQueryOptions['table']['prefix'] = $config['storage']['big_query']['table']['attribute']['prefix'];
            $bigQueryOptions['table']['attribute_name'] = $config['storage']['big_query']['table']['attribute']['attribute_name'];
        }

        if ($bigQueryOptions['table']['mode'] == 'service') {
            $bigQueryOptions['table']['service'] = $config['storage']['big_query']['table']['service'];
        }

        // SET SCHEMA OPTIONS

        $bigQueryFields = [
            ['name' => 'id',            'type' => 'integer',  'mode' => 'required'],
            ['name' => 'timestamp',     'type' => 'integer',  'mode' => 'required'],
            ['name' => 'entity_class',  'type' => 'string',   'mode' => ''],
            ['name' => 'entity_id',     'type' => 'string',   'mode' => ''],
            ['name' => 'changes',       'type' => 'string',   'mode' => ''],
        ];

        if ($config['collect']['user']) {
            $bigQueryFields[] = ['name' => 'username', 'type' => 'string', 'mode' => ''];
        }

        if ($config['collect']['request']) {
            $bigQueryFields[] = ['name' => 'request_ip',     'type' => 'string', 'mode' => ''];
            $bigQueryFields[] = ['name' => 'user_agent',     'type' => 'string', 'mode' => ''];
            $bigQueryFields[] = ['name' => 'request_method', 'type' => 'string', 'mode' => ''];
            $bigQueryFields[] = ['name' => 'request_path',   'type' => 'string', 'mode' => ''];
        }

        if ($config['collect']['action']) {
            $bigQueryFields[] = ['name' => 'action', 'type' => 'string', 'mode' => ''];
        }

        if (isset($config['storage']['big_query']['schema']['extra_fields'])) {
            $bigQueryFields = array_merge($bigQueryFields, $config['storage']['big_query']['schema']['extra_fields']);
        }

        $bigQueryOptions['schema']['fields'] = $bigQueryFields;

        $container->setParameter('sfs_doctrine_changelog.storage.big_query', $bigQueryOptions);
    }
}