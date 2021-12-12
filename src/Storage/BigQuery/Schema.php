<?php

namespace Softspring\DoctrineChangeLogBundle\Storage\BigQuery;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\Table;
use Psr\Log\LoggerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class Schema
{
    /**
     * @var BigQueryClient
     */
    protected $bigQueryClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Schema constructor.
     * @param BigQueryClient $bigQueryClient
     * @param array $config
     * @param LoggerInterface $logger
     */
    public function __construct(BigQueryClient $bigQueryClient, array $config, LoggerInterface $logger)
    {
        $this->bigQueryClient = $bigQueryClient;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * @param string $name
     * @return Table
     */
    public function getTable(string $name): Table
    {
        return $this->getOrCreateTable($name);
    }

    /**
     * @return Dataset
     */
    public function getDataset(): Dataset
    {
        return $this->getOrCreateDataset();
    }

    /**
     * @param string $fieldName
     * @return bool
     */
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
     * @param ChangeEntry $entry
     * @return string
     * @throws \Exception
     */
    public function getTargetTable(ChangeEntry $entry): string
    {
        if ($this->config['table']['mode'] == 'fixed') {
            return $this->config['table']['name'];
        }

        if ($this->config['table']['mode'] == 'service') {
            throw new \Exception('Table mode by service is not yet implemented');
        }

        if ($this->config['table']['mode'] == 'attribute') {
            $prefix = $this->config['table']['prefix'];
            $attr = $this->config['table']['attribute_name'];

            if (!$entry->getAttributes()->has($attr)) {
                return '__default';
            }

            $attrValue = $entry->getAttributes()->get($attr);

            return ($prefix?$prefix:'').$attrValue;
        }

        return '__default';
    }

    /**
     * @return Dataset
     */
    private function getOrCreateDataset(): Dataset
    {
        $dataset = $this->bigQueryClient->dataset($this->config['dataset']);

        if (!$dataset->exists()) {
            $dataset = $this->bigQueryClient->createDataset($this->config['dataset']);
        }

        return $dataset;
    }

    /**
     * @param string $name
     * @return Table
     */
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