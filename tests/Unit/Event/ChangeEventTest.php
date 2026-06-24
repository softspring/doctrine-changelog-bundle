<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Event;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;

class ChangeEventTest extends TestCase
{
    public function testStoresIdentifierEntityAndEntry(): void
    {
        $entity = new RegistrableAttributeEntity();
        $changes = ['name' => ['old', 'new']];

        $event = new UpdateChangeEvent(['id' => 7], $entity, $changes);

        $this->assertSame(['id' => 7], $event->getIdentifier());
        $this->assertSame($entity, $event->getEntity());
        $this->assertSame(['id' => 7], $event->getEntry()->getEntityIdentifier());
        $this->assertSame(RegistrableAttributeEntity::class, $event->getEntry()->getEntityClass());
        $this->assertSame($changes, $event->getEntry()->getChanges());
    }

    public function testConcreteChangeEventsShareBaseBehaviour(): void
    {
        $entity = new RegistrableAttributeEntity();

        $this->assertSame($entity, (new InsertionChangeEvent(['id' => 1], $entity, []))->getEntity());
        $this->assertSame($entity, (new DeletionChangeEvent(['id' => 1], $entity, []))->getEntity());
    }
}
