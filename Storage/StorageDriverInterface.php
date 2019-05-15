<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Softspring\DoctrineChangeLogBundle\Collector\Changes;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;

interface StorageDriverInterface
{
    /**
     * @param Changes $changes
     */
    public function save(Changes $changes): void;

    /**
     * @param ChangesStack $changesStack
     */
    public function saveStack(ChangesStack $changesStack): void;
}