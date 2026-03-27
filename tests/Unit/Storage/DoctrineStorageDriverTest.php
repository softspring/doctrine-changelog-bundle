<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Storage;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Storage\DoctrineStorageDriver;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\TestChangeLog;

class DoctrineStorageDriverTest extends TestCase
{
    public function testSaveUsesConfiguredChangeLogClass(): void
    {
        $entry = new ChangeEntry(['id' => 1], 'EntityA', []);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($this->callback(fn (mixed $changeLog): bool => $changeLog instanceof TestChangeLog));
        $em->expects($this->once())->method('flush');

        $driver = new DoctrineStorageDriver($em, TestChangeLog::class);
        $driver->save($entry);
    }

    public function testSaveStackPersistsAllEntries(): void
    {
        $stack = new ChangesStack();
        $stack->push(new ChangeEntry(['id' => 1], 'EntityA', []));
        $stack->push(new ChangeEntry(['id' => 2], 'EntityB', []));

        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->exactly(2))
            ->method('persist')
            ->with($this->callback(fn (mixed $changeLog): bool => $changeLog instanceof TestChangeLog));
        $em->expects($this->once())->method('flush');

        $driver = new DoctrineStorageDriver($em, TestChangeLog::class);
        $driver->saveStack($stack);

        $this->assertCount(0, $stack);
    }
}
