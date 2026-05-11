<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Collector;

use Countable;

class ChangesStack implements Countable
{
    /**
     * @var ChangeEntry[]
     */
    protected array $entries = [];

    public function count(): int
    {
        return count($this->entries);
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
