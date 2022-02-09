<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
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
     */
    public function __construct(ChangesStack $changesStack)
    {
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::UPDATE => [
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::DELETION => [
                ['onChangeCollectEvent', -100],
            ],
        ];
    }

    public function onChangeCollectEvent(AbstractChangeEvent $event)
    {
        $this->changesStack->push($event->getEntry());
    }
}
