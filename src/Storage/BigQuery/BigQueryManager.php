<?php

namespace Softspring\DoctrineChangeLogBundle\Storage\BigQuery;

use Psr\Log\LoggerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class BigQueryManager
{
    protected Schema $schema;

    protected ChangesProcessor $changesProcessor;

    protected LoggerInterface $logger;

    public function __construct(Schema $schema, ChangesProcessor $changesProcessor, LoggerInterface $logger)
    {
        $this->schema = $schema;
        $this->changesProcessor = $changesProcessor;
        $this->logger = $logger;
    }

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
                    foreach ($response->info()['insertErrors'] as $insertError) {
                        if (1 == sizeof($insertError['errors'])) {
                            $err = $insertError['errors'][0];
                            $this->logger->error(sprintf('BigQuery error inserting row, reason: %s location: %s, message: %s', $err['reason'], $err['location'], $err['message']));
                        } else {
                            $this->logger->error(sprintf('BigQuery error inserting row because of multiple errors'));
                        }
                    }
                    $successful = false;
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
                $successful = false;
            }
        }

        return $successful;
    }
}
