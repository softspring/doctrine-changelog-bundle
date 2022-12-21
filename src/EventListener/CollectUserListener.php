<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CollectUserListener implements EventSubscriberInterface
{
    protected ?TokenStorageInterface $tokenStorage;

    public function __construct(?TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [['onChangeAddUsername', 98]],
            SfsDoctrineChangeLogEvents::UPDATE => [['onChangeAddUsername', 98]],
            SfsDoctrineChangeLogEvents::DELETION => [['onChangeAddUsername', 98]],
        ];
    }

    public function onChangeAddUsername(AbstractChangeEvent $event): void
    {
        if (!$this->tokenStorage instanceof TokenStorageInterface) {
            return;
        }

        if (!$token = $this->tokenStorage->getToken()) {
            return;
        }

        $event->getEntry()->getAttributes()->set('user', $token->getUserIdentifier());
    }
}
