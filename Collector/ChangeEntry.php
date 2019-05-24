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
     * @param array  $entityIdentifier
     * @param string $entityClass
     * @param array  $changes
     * @param array  $attributes
     */
    public function __construct(array $entityIdentifier, string $entityClass, array $changes, array $attributes = [])
    {
        $this->timestamp = time();
        $this->changes = $changes;
        $this->entityClass = $entityClass;
        $this->entityIdentifier = $entityIdentifier;
        $this->attributes = new ParameterBag($attributes);
    }

    /**
     * @return array
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    /**
     * @return ParameterBag
     */
    public function getAttributes(): ParameterBag
    {
        return $this->attributes;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @return array
     */
    public function getEntityIdentifier(): array
    {
        return $this->entityIdentifier;
    }
}