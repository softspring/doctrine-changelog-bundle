<?php

namespace Softspring\DoctrineChangeLogBundle\Collector;

use Symfony\Component\HttpFoundation\ParameterBag;

class ChangeEntry
{
    /**
     * @var int
     */
    protected $timestamp;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $entityIdentifier;

    /**
     * @var array
     */
    protected $changes = [];

    /**
     * @var ParameterBag
     */
    protected $attributes;

    /**
     * ChangeLog constructor.
     */
    public function __construct(array $entityIdentifier, string $entityClass, array $changes, array $attributes = [])
    {
        $this->timestamp = time();
        $this->changes = $changes;
        $this->entityClass = $entityClass;
        $this->entityIdentifier = $entityIdentifier;
        $this->attributes = new ParameterBag($attributes);
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function getAttributes(): ParameterBag
    {
        return $this->attributes;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getEntityIdentifier(): array
    {
        return $this->entityIdentifier;
    }
}
