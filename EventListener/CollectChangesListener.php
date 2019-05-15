<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CollectChangesListener implements EventSubscriberInterface
{
    /**
     * @var ChangesStack
     */
    protected $changesStack;

    /**
     * CollectChangesListener constructor.
     * @param ChangesStack $changesStack
     */
    public function __construct(ChangesStack $changesStack)
    {
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [
                ['onInsertionAddAction', 100],
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::UPDATE => [
                ['onUpdateAddAction', 100],
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::DELETION => [
                ['onDeletionAddAction', 100],
                ['onChangeCollectEvent', -100],
            ],
        ];
    }

    public function onInsertionAddAction(InsertionChangeEvent $event)
    {
        $event->getChanges()->getAttributes()->set('action', 'insertion');
    }

    public function onUpdateAddAction(UpdateChangeEvent $event)
    {
        $event->getChanges()->getAttributes()->set('action', 'update');
    }

    public function onDeletionAddAction(DeletionChangeEvent $event)
    {
        $event->getChanges()->getAttributes()->set('action', 'deletion');
    }

    public function onChangeCollectEvent(AbstractChangeEvent $event)
    {
        $this->changesStack->push($event->getChanges());
    }
}