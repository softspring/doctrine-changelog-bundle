<?php

namespace Softspring\DoctrineChangeLogBundle\Storage\BigQuery;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\Table;
use Psr\Log\LoggerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class Schema
{
    protected BigQueryClient $bigQueryClient;

    protected array $config;

    protected LoggerInterface $logger;

    /**
     * Schema constructor.
     */
    public function __construct(BigQueryClient $bigQueryClient, array $config, LoggerInterface $logger)
    {
        $this->bigQueryClient = $bigQueryClient;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function getTable(string $name): Table
    {
        return $this->getOrCreateTable($name);
    }

    public function getDataset(): Dataset
    {
        return $this->getOrCreateDataset();
    }

    public function fieldDefined(string $fieldName): bool
    {
        foreach ($this->config['schema']['fields'] as $field) {
            if ($field['name'] == $fieldName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function getTargetTable(ChangeEntry $entry): string
    {
        if ('fixed' == $this->config['table']['mode']) {
            return $this->config['table']['name'];
        }

        if ('service' == $this->config['table']['mode']) {
            throw new \Exception('Table mode by service is not yet implemented');
        }

        if ('attribute' == $this->config['table']['mode']) {
            $prefix = $this->config['table']['prefix'];
            $attr = $this->config['table']['attribute_name'];

            if (!$entry->getAttributes()->has($attr)) {
                return '__default';
            }

            $attrValue = $entry->getAttributes()->get($attr);

            return ($prefix ? $prefix : '').$attrValue;
        }

        return '__default';
    }

    private function getOrCreateDataset(): Dataset
    {
        $dataset = $this->bigQueryClient->dataset($this->config['dataset']);

        if (!$dataset->exists()) {
            $dataset = $this->bigQueryClient->createDataset($this->config['dataset']);
        }

        return $dataset;
    }

    private function getOrCreateTable(string $name): Table
    {
        $table = $this->getDataset()->table($name);

        if (!$table->exists()) {
            $this->logger->info(sprintf('BigQuery table %s does not exists', $name));
            $table = $this->getDataset()->createTable($name, ['schema' => $this->config['schema']]);
        } else {
            // TODO TEST IF UPDATE IS NEEDED
        }

        return $table;
    }
}
