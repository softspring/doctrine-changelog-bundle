<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\EventListener\ChangesPersistListener;
use Softspring\DoctrineChangeLogBundle\Storage\StorageDriverInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ChangesPersistListenerTest extends TestCase
{
    public function testSubscribesToKernelTerminate(): void
    {
        $this->assertSame([
            KernelEvents::TERMINATE => [
                ['onTerminateStoreStack', 0],
            ],
        ], ChangesPersistListener::getSubscribedEvents());
    }

    public function testDoesNotStoreEmptyStack(): void
    {
        $stack = new ChangesStack();
        $driver = $this->createMock(StorageDriverInterface::class);
        $driver->expects($this->never())->method('saveStack');

        (new ChangesPersistListener($driver, $stack))->onTerminateStoreStack();
    }

    public function testStoresNonEmptyStack(): void
    {
        $stack = new ChangesStack();
        $stack->push(new ChangeEntry(['id' => 1], 'EntityA', []));

        $driver = $this->createMock(StorageDriverInterface::class);
        $driver->expects($this->once())->method('saveStack')->with($stack);

        (new ChangesPersistListener($driver, $stack))->onTerminateStoreStack();
    }
}
