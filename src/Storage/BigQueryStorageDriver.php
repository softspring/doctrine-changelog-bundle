<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Storage\BigQuery\BigQueryManager;

class BigQueryStorageDriver implements StorageDriverInterface
{
    /**
     * @var BigQueryManager
     */
    protected $manager;

    /**
     * BigQueryStorageDriver constructor.
     * @param BigQueryManager $manager
     */
    public function __construct(BigQueryManager $manager)
    {
        $this->manager = $manager;
    }

    public function save(ChangeEntry $entry): void
    {
        $this->manager->insertEntry($entry);
    }

    public function saveStack(ChangesStack $changesStack): void
    {
        $this->manager->insertEntries($changesStack->popAll());
    }
}