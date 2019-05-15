<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Storage\StorageDriverInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ChangesPersistListener implements EventSubscriberInterface
{
    /**
     * @var StorageDriverInterface
     */
    protected $storageDriver;

    /**
     * @var ChangesStack
     */
    protected $changesStack;

    /**
     * ChangesPersistListener constructor.
     * @param StorageDriverInterface $storageDriver
     * @param ChangesStack $changesStack
     */
    public function __construct(StorageDriverInterface $storageDriver, ChangesStack $changesStack)
    {
        $this->storageDriver = $storageDriver;
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::FINISH_REQUEST => [
                [ 'onTerminateStoreStack', 0 ]
            ],

            // TODO also for KernelEvents::EXCEPTION
        ];
    }

    public function onTerminateStoreStack()
    {
        $this->storageDriver->saveStack($this->changesStack);
    }
}