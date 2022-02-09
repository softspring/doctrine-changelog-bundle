<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;

interface StorageDriverInterface
{
    public function save(ChangeEntry $entry): void;

    public function saveStack(ChangesStack $changesStack): void;
}
