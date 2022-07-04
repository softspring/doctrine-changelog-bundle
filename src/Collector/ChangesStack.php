<?php

namespace Softspring\DoctrineChangeLogBundle\Collector;

class ChangesStack implements \Countable
{
    /**
     * @var ChangeEntry[]
     */
    protected array $entries;

    public function __construct()
    {
        $this->entries = [];
    }

    public function count(): int
    {
        return sizeof($this->entries);
    }

    public function push(ChangeEntry $entry): void
    {
        $this->entries[] = $entry;
    }

    public function pop(): ?ChangeEntry
    {
        return array_pop($this->entries);
    }

    public function popAll(): array
    {
        $entries = $this->entries;

        $this->entries = [];

        return $entries;
    }
}
