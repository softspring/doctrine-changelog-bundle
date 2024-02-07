<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectActionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            InsertionChangeEvent::class => [
                ['onInsertionAddAction', 100],
            ],
            UpdateChangeEvent::class => [
                ['onUpdateAddAction', 100],
            ],
            DeletionChangeEvent::class => [
                ['onDeletionAddAction', 100],
            ],
        ];
    }

    public function onInsertionAddAction(InsertionChangeEvent $event): void
    {
        $event->getEntry()->getAttributes()->set('action', 'insertion');
    }

    public function onUpdateAddAction(UpdateChangeEvent $event): void
    {
        $event->getEntry()->getAttributes()->set('action', 'update');
    }

    public function onDeletionAddAction(DeletionChangeEvent $event): void
    {
        $event->getEntry()->getAttributes()->set('action', 'deletion');
    }
}
