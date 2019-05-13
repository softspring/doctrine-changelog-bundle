<?php

namespace Softspring\DoctrineChangeLogBundle\Collector;

use Softspring\DoctrineChangeLogBundle\Model\ChangeLog;

class ChangesStack
{
    /**
     * @var ChangeLog[]
     */
    protected $changes;

    /**
     * ChangesCollector constructor.
     */
    public function __construct()
    {
        $this->changes = [];
    }

    public function push(ChangeLog $change)
    {
        array_push($this->changes, $change);
    }

    public function pop(): ChangeLog
    {
        return array_pop($this->changes);
    }
}