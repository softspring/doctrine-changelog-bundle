<?php

namespace Softspring\DoctrineChangeLogBundle\Event;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Symfony\Component\EventDispatcher\Event;

abstract class AbstractChangeEvent extends Event
{
    /**
     * @var array
     */
    protected $identifier;

    /**
     * @var object
     */
    protected $entity;

    /**
     * @var ChangeEntry
     */
    protected $entry;

    /**
     * AbstractChangeEvent constructor.
     * @param array $identifier
     * @param object $entity
     * @param array $changes
     */
    public function __construct(array $identifier, object $entity, array $changes)
    {
        $this->identifier = $identifier;
        $this->entity = $entity;
        $this->entry = new ChangeEntry($identifier, get_class($entity), $changes);
    }

    /**
     * @return array
     */
    public function getIdentifier(): array
    {
        return $this->identifier;
    }

    /**
     * @return object
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return ChangeEntry
     */
    public function getEntry(): ChangeEntry
    {
        return $this->entry;
    }
}