<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Storage\StorageDriverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ChangesPersistListener implements EventSubscriberInterface
{
    protected StorageDriverInterface $storageDriver;

    protected ChangesStack $changesStack;

    public function __construct(StorageDriverInterface $storageDriver, ChangesStack $changesStack)
    {
        $this->storageDriver = $storageDriver;
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::TERMINATE => [
                ['onTerminateStoreStack', 0],
            ],

            // TODO also for KernelEvents::EXCEPTION
        ];
    }

    public function onTerminateStoreStack(): void
    {
        if ($this->changesStack->count()) {
            $this->storageDriver->saveStack($this->changesStack);
        }
    }
}
