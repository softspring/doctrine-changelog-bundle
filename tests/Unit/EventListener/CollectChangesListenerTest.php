<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\EventListener\CollectChangesListener;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;

class CollectChangesListenerTest extends TestCase
{
    public function testSubscribesToChangeEvents(): void
    {
        $this->assertSame([
            InsertionChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
            UpdateChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
            DeletionChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
        ], CollectChangesListener::getSubscribedEvents());
    }

    public function testCollectsEventEntryIntoStack(): void
    {
        $stack = new ChangesStack();
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), ['name' => ['old', 'new']]);

        (new CollectChangesListener($stack))->onChangeCollectEvent($event);

        $this->assertSame($event->getEntry(), $stack->pop());
    }
}
