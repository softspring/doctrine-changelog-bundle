<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CollectRequestListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * CollectRequestListener constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [ ['onChangeAddRequest', 99] ],
            SfsDoctrineChangeLogEvents::UPDATE => [ ['onChangeAddRequest', 99] ],
            SfsDoctrineChangeLogEvents::DELETION => [ ['onChangeAddRequest', 99] ],
        ];
    }

    public function onChangeAddRequest(AbstractChangeEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request instanceof Request) {
            return;
        }

        $event->getChanges()->getAttributes()->set('client_ip', $request->getClientIp());
        $event->getChanges()->getAttributes()->set('user_agent', $request->headers->get('User-Agent'));
        $event->getChanges()->getAttributes()->set('request_method', $request->getMethod());
        $event->getChanges()->getAttributes()->set('request_path', $request->getPathInfo());
    }
}