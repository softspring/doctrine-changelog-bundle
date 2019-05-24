<?php

namespace Softspring\DoctrineChangeLogBundle\Collector;

class ChangesStack implements \Countable
{
    /**
     * @var Changes[]
     */
    protected $changes;

    /**
     * ChangesCollector constructor.
     */
    public function __construct()
    {
        $this->changes = [];
    }

    public function count()
    {
        return sizeof($this->changes);
    }

    public function push(Changes $change)
    {
        array_push($this->changes, $change);
    }

    public function pop(): ?Changes
    {
        return array_pop($this->changes);
    }
}