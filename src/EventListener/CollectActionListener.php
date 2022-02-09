<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectActionListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [
                ['onInsertionAddAction', 100],
            ],
            SfsDoctrineChangeLogEvents::UPDATE => [
                ['onUpdateAddAction', 100],
            ],
            SfsDoctrineChangeLogEvents::DELETION => [
                ['onDeletionAddAction', 100],
            ],
        ];
    }

    public function onInsertionAddAction(InsertionChangeEvent $event)
    {
        $event->getEntry()->getAttributes()->set('action', 'insertion');
    }

    public function onUpdateAddAction(UpdateChangeEvent $event)
    {
        $event->getEntry()->getAttributes()->set('action', 'update');
    }

    public function onDeletionAddAction(DeletionChangeEvent $event)
    {
        $event->getEntry()->getAttributes()->set('action', 'deletion');
    }
}
