<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Google\Cloud\BigQuery\BigQueryClient;
use Google\Cloud\BigQuery\Dataset;
use Google\Cloud\BigQuery\Table;
use Softspring\DoctrineChangeLogBundle\Collector\Changes;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;

class BigQueryStorageDriver implements StorageDriverInterface
{
    /**
     * @var BigQueryClient
     */
    protected $bigQueryClient;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $schema;

    /**
     * BigQueryStorageDriver constructor.
     * @param BigQueryClient $bigQueryClient
     * @param array $options
     * @param array $schema
     */
    public function __construct(BigQueryClient $bigQueryClient, array $options, array $schema)
    {
        $this->bigQueryClient = $bigQueryClient;
        $this->options = $options;
        $this->schema = $schema;
    }

    public function save(Changes $changes): void
    {
        try {
            $response = $this->getTable()->insertRow($this->getChangesArray($changes));

            if (!$response->isSuccessful()) {
                // TODO LOG ERRORS
            }
        } catch (\Exception $e) {
            $e->getCode();
        }
    }

    public function saveStack(ChangesStack $changesStack): void
    {
        $rows = [];

        /** @var Changes $changes */
        while ($changes = $changesStack->pop()) {
            $rows[] = $this->getChangesArray($changes);
        }

        try {
            $response = $this->getTable()->insertRows($rows);

            if (!$response->isSuccessful()) {
                // TODO LOG ERRORS
            }
        } catch (\Exception $e) {
            $e->getCode();
        }
    }

    protected function getChangesArray(Changes $changes): array
    {
        // basic fields
        $data = [
            'id' => time(),
            'timestamp' => $changes->getTimestamp(),
            'entity_class' => $changes->getEntityClass(),
            'entity_id' => json_encode($changes->getEntityIdentifier()),
            'changes' => json_encode($changes->getChanges()),
        ];

        // TODO check if user collector is present
        $data['username'] = $changes->getAttributes()->get('username');

        // TODO check if request collector is present
        $data['request_ip'] = $changes->getAttributes()->get('request_ip');
        $data['user_agent'] = $changes->getAttributes()->get('user_agent');
        $data['request_method'] = $changes->getAttributes()->get('request_method');
        $data['request_path'] = $changes->getAttributes()->get('request_path');

        // TODO check if action collector is present
        $data['action'] = $changes->getAttributes()->get('action');

        $missingAttributes = array_diff(array_keys($changes->getAttributes()->all()), ['id', 'timestamp', 'entity_class', 'entity_id', 'changes', 'username', 'request_ip', 'user_agent', 'request_method', 'request_path', 'action']);

        foreach ($missingAttributes as $attributeKey) {
            if ($this->schemaHasField($attributeKey)) {
                $data[$attributeKey] = $changes->getAttributes()->get($attributeKey);
            } else {
                // TODO LOG MISSING FIELD
                $missingSchemaField = $attributeKey;
            }
        }

        return ['data' => $data];
    }

    protected function schemaHasField(string $attributeKey): bool
    {
        foreach ($this->schema['fields'] as $field) {
            if ($field['name'] == $attributeKey) {
                return true;
            }
        }

        return false;
    }

    protected function getTable(): Table
    {
        $table = $this->getDataset()->table($this->options['table']);

        if (!$table->exists()) {
            // TODO LOG THIS OR EXCEPTION ??
        }

        return $table;
    }

    protected function getDataset(): Dataset
    {
        return $this->bigQueryClient->dataset($this->options['dataset']);
    }
}