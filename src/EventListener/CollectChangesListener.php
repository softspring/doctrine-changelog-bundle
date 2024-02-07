<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectChangesListener implements EventSubscriberInterface
{
    protected ChangesStack $changesStack;

    public function __construct(ChangesStack $changesStack)
    {
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            InsertionChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
            UpdateChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
            DeletionChangeEvent::class => [
                ['onChangeCollectEvent', -100],
            ],
        ];
    }

    public function onChangeCollectEvent(AbstractChangeEvent $event): void
    {
        $this->changesStack->push($event->getEntry());
    }
}
