<?php

namespace Softspring\DoctrineChangeLogBundle\Collector;

use Symfony\Component\HttpFoundation\ParameterBag;

class ChangeEntry
{
    protected int $timestamp;

    protected string $entityClass;

    protected array $entityIdentifier;

    protected array $changes = [];

    protected ParameterBag $attributes;

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
