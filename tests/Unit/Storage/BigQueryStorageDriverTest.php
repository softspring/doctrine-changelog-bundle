<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Storage;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Storage\BigQuery\BigQueryManager;
use Softspring\DoctrineChangeLogBundle\Storage\BigQueryStorageDriver;

class BigQueryStorageDriverTest extends TestCase
{
    public function testDelegatesSaveAndSaveStack(): void
    {
        $entry = new ChangeEntry(['id' => 1], 'EntityA', []);
        $stack = new ChangesStack();
        $stack->push($entry);

        $manager = $this->createMock(BigQueryManager::class);
        $manager->expects($this->once())->method('insertEntry')->with($entry);
        $manager->expects($this->once())->method('insertEntries')->with([$entry]);

        $driver = new BigQueryStorageDriver($manager);
        $driver->save($entry);
        $driver->saveStack($stack);
    }
}
