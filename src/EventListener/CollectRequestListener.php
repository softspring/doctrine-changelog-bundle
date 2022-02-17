<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Softspring\DoctrineChangeLogBundle\Event\AbstractChangeEvent;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CollectRequestListener implements EventSubscriberInterface
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SfsDoctrineChangeLogEvents::INSERTION => [['onChangeAddRequest', 99]],
            SfsDoctrineChangeLogEvents::UPDATE => [['onChangeAddRequest', 99]],
            SfsDoctrineChangeLogEvents::DELETION => [['onChangeAddRequest', 99]],
        ];
    }

    public function onChangeAddRequest(AbstractChangeEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request instanceof Request) {
            return;
        }

        $event->getEntry()->getAttributes()->set('request_ip', $request->getClientIp());
        $event->getEntry()->getAttributes()->set('user_agent', $request->headers->get('User-Agent'));
        $event->getEntry()->getAttributes()->set('request_method', $request->getMethod());
        $event->getEntry()->getAttributes()->set('request_path', $request->getPathInfo());
    }
}
