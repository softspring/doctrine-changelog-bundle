<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
            InsertionChangeEvent::class => [['onChangeAddUsername', 98]],
            UpdateChangeEvent::class => [['onChangeAddUsername', 98]],
            DeletionChangeEvent::class => [['onChangeAddUsername', 98]],
        ];
    }

    public function onChangeAddUsername(AbstractChangeEvent $event): void
    {
        if (!$this->tokenStorage instanceof TokenStorageInterface) {
            return;
        }

        if (!($token = $this->tokenStorage->getToken()) instanceof TokenInterface) {
            return;
        }

        $event->getEntry()->getAttributes()->set('username', $token->getUserIdentifier());
    }
}
