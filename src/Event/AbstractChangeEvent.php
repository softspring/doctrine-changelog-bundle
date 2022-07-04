<?php

namespace Softspring\DoctrineChangeLogBundle\Event;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Symfony\Contracts\EventDispatcher\Event;

abstract class AbstractChangeEvent extends Event
{
    protected array $identifier;

    protected object $entity;

    protected ChangeEntry $entry;

    public function __construct(array $identifier, object $entity, array $changes)
    {
        $this->identifier = $identifier;
        $this->entity = $entity;
        $this->entry = new ChangeEntry($identifier, get_class($entity), $changes);
    }

    public function getIdentifier(): array
    {
        return $this->identifier;
    }

    public function getEntity(): object
    {
        return $this->entity;
    }

    public function getEntry(): ChangeEntry
    {
        return $this->entry;
    }
}
