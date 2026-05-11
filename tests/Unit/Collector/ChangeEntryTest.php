<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Collector;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

class ChangeEntryTest extends TestCase
{
    public function testStoresCoreDataAndAttributes(): void
    {
        $entry = new ChangeEntry(['id' => 5], 'App\\Entity\\Product', ['name' => ['old', 'new']], ['action' => 'update']);

        $this->assertSame(['id' => 5], $entry->getEntityIdentifier());
        $this->assertSame('App\\Entity\\Product', $entry->getEntityClass());
        $this->assertSame(['name' => ['old', 'new']], $entry->getChanges());
        $this->assertSame('update', $entry->getAttributes()->get('action'));
        $this->assertGreaterThan(0, $entry->getTimestamp());
    }
}
