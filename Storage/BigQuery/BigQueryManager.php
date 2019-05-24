<?php

namespace Softspring\DoctrineChangeLogBundle\Storage\BigQuery;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class BigQueryManager
{
    /**
     * @var Schema
     */
    protected $schema;

    /**
     * @var ChangesProcessor
     */
    protected $changesProcessor;

    /**
     * BigQueryManager constructor.
     * @param Schema $schema
     * @param ChangesProcessor $changesProcessor
     */
    public function __construct(Schema $schema, ChangesProcessor $changesProcessor)
    {
        $this->schema = $schema;
        $this->changesProcessor = $changesProcessor;
    }

    /**
     * @param ChangeEntry $entry
     * @return bool
     */
    public function insertEntry(ChangeEntry $entry): bool
    {
        try {
            $tableName = $this->schema->getTargetTable($entry);
            $table = $this->schema->getTable($tableName);

            $response = $table->insertRow($this->changesProcessor->getDataRow($entry));

            if (!$response->isSuccessful()) {
                // TODO LOG ERRORS
                return false;
            }
        } catch (\Exception $e) {
            // TODO PROCESS EXCEPTION
            return false;
        }

        return true;
    }

    /**
     * @param array $entries
     * @return bool
     * @throws \Exception
     */
    public function insertEntries(array $entries): bool
    {
        $changesByTable = [];
        foreach ($entries as $entry) {
            $tableName = $this->schema->getTargetTable($entry);
            $changesByTable[$tableName][] = $this->changesProcessor->getDataRow($entry);
        }

        $successful = true;
        foreach ($changesByTable as $tableName => $rows) {
            try {
                $table = $this->schema->getTable($tableName);
                $response = $table->insertRows($rows);

                if (!$response->isSuccessful()) {
                    // TODO LOG ERRORS
                    $successful = false;
                }
            } catch (\Exception $e) {
                // TODO PROCESS EXCEPTION
                $successful = false;
            }
        }

        return $successful;
    }
}