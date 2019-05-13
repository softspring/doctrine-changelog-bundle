<?php

namespace Softspring\DoctrineChangeLogBundle\Event;

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
     * @var array
     */
    protected $changes;

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
        $this->changes = $changes;
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
     * @return array
     */
    public function getChanges(): array
    {
        return $this->changes;
    }
}