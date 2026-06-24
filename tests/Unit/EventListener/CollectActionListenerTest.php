<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\EventListener\CollectActionListener;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;

class CollectActionListenerTest extends TestCase
{
    public function testSubscribesToChangeEvents(): void
    {
        $this->assertSame([
            InsertionChangeEvent::class => [
                ['onInsertionAddAction', 100],
            ],
            UpdateChangeEvent::class => [
                ['onUpdateAddAction', 100],
            ],
            DeletionChangeEvent::class => [
                ['onDeletionAddAction', 100],
            ],
        ], CollectActionListener::getSubscribedEvents());
    }

    public function testAddsActionAttributeForEachChangeKind(): void
    {
        $listener = new CollectActionListener();
        $entity = new RegistrableAttributeEntity();

        $insertion = new InsertionChangeEvent(['id' => 1], $entity, []);
        $listener->onInsertionAddAction($insertion);

        $update = new UpdateChangeEvent(['id' => 1], $entity, []);
        $listener->onUpdateAddAction($update);

        $deletion = new DeletionChangeEvent(['id' => 1], $entity, []);
        $listener->onDeletionAddAction($deletion);

        $this->assertSame('insertion', $insertion->getEntry()->getAttributes()->get('action'));
        $this->assertSame('update', $update->getEntry()->getAttributes()->get('action'));
        $this->assertSame('deletion', $deletion->getEntry()->getAttributes()->get('action'));
    }
}
