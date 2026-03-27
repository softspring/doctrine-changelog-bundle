<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\TestChangeLog;

class ChangeLogTest extends TestCase
{
    public function testCreateMapsEntryDataAndUsesLateStaticBinding(): void
    {
        $entry = new ChangeEntry(['id' => 5], 'App\\Entity\\Product', ['name' => ['old', 'new']], [
            'action' => 'update',
            'username' => 'alice@example.com',
            'request_ip' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'request_method' => 'POST',
            'request_path' => '/products/5',
        ]);

        $changeLog = TestChangeLog::create($entry);

        $this->assertInstanceOf(TestChangeLog::class, $changeLog);
        $this->assertSame('alice@example.com', $changeLog->getUsername());
        $this->assertSame('127.0.0.1', $changeLog->getIp());
        $this->assertSame('PHPUnit', $changeLog->getUserAgent());
        $this->assertSame('POST', $changeLog->getRequestMethod());
        $this->assertSame('/products/5', $changeLog->getRequestPath());
        $this->assertSame('update', $changeLog->getAction());
        $this->assertSame(['id' => 5], $changeLog->getEntityId());
    }
}
