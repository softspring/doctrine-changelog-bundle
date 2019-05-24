<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;

interface StorageDriverInterface
{
    /**
     * @param ChangeEntry $entry
     */
    public function save(ChangeEntry $entry): void;

    /**
     * @param ChangesStack $changesStack
     */
    public function saveStack(ChangesStack $changesStack): void;
}