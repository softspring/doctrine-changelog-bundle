<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CollectUserListener implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface|null
     */
    protected $tokenStorage;

    /**
     * CollectUserListener constructor.
     * @param TokenStorageInterface|null $tokenStorage
     */
    public function __construct(?TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [ ['onChangeAddUsername', 98] ],
            SfsDoctrineChangeLogEvents::UPDATE => [ ['onChangeAddUsername', 98] ],
            SfsDoctrineChangeLogEvents::DELETION => [ ['onChangeAddUsername', 98] ],
        ];
    }

    public function onChangeAddUsername(AbstractChangeEvent $event)
    {
        if (!$this->tokenStorage instanceof TokenStorageInterface) {
            return;
        }

        if (! $token = $this->tokenStorage->getToken()) {
            return;
        }

        $event->getChanges()->getAttributes()->set('username', $token->getUsername() ?? null);
    }
}