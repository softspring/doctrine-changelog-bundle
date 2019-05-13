<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChangesCollectorListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TokenStorageInterface|null
     */
    protected $tokenStorage;

    /**
     * @var ChangesStack
     */
    protected $changesStack;

    /**
     * ChangesCollectorListener constructor.
     * @param RequestStack $requestStack
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface|null $tokenStorage
     * @param ChangesStack $changesStack
     */
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em, ?TokenStorageInterface $tokenStorage, ChangesStack $changesStack)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->changesStack = $changesStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [
                ['onInsertionAddAction', 100],
                ['onChangeAddRequest', 99],
                ['onChangeAddUsername', 98],
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::UPDATE => [
                ['onUpdateAddAction', 100],
                ['onChangeAddRequest', 99],
                ['onChangeAddUsername', 98],
                ['onChangeCollectEvent', -100],
            ],
            SfsDoctrineChangeLogEvents::DELETION => [
                ['onDeletionAddAction', 100],
                ['onChangeAddRequest', 99],
                ['onChangeAddUsername', 98],
                ['onChangeCollectEvent', -100],
            ],
        ];
    }

    public function onInsertionAddAction(InsertionChangeEvent $event)
    {
        $event->getChangeLog()->getAttributes()->set('action', 'insertion');
    }

    public function onUpdateAddAction(UpdateChangeEvent $event)
    {
        $event->getChangeLog()->getAttributes()->set('action', 'update');
    }

    public function onDeletionAddAction(DeletionChangeEvent $event)
    {
        $event->getChangeLog()->getAttributes()->set('action', 'deletion');
    }

    public function onChangeAddRequest(AbstractChangeEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request instanceof Request) {
            return;
        }

        $event->getChangeLog()->getAttributes()->set('client_ip', $request->getClientIp());
        $event->getChangeLog()->getAttributes()->set('user_agent', $request->headers->get('User-Agent'));
        $event->getChangeLog()->getAttributes()->set('request_method', $request->getMethod());
        $event->getChangeLog()->getAttributes()->set('request_path', $request->getPathInfo());
    }

    public function onChangeAddUsername(AbstractChangeEvent $event)
    {
        if (!$this->tokenStorage instanceof TokenStorageInterface) {
            return;
        }

        $event->getChangeLog()->getAttributes()->set('username', $this->tokenStorage->getToken()->getUsername() ?? null);
    }

    public function onChangeCollectEvent(AbstractChangeEvent $event)
    {
        $this->changesStack->push($event->getChangeLog());
    }
}