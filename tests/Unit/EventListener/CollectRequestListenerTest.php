<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\EventListener\CollectRequestListener;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CollectRequestListenerTest extends TestCase
{
    public function testSubscribesToChangeEvents(): void
    {
        $this->assertSame([
            InsertionChangeEvent::class => [['onChangeAddRequest', 99]],
            UpdateChangeEvent::class => [['onChangeAddRequest', 99]],
            DeletionChangeEvent::class => [['onChangeAddRequest', 99]],
        ], CollectRequestListener::getSubscribedEvents());
    }

    public function testDoesNothingWithoutCurrentRequest(): void
    {
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), []);

        (new CollectRequestListener(new RequestStack()))->onChangeAddRequest($event);

        $this->assertSame([], $event->getEntry()->getAttributes()->all());
    }

    public function testAddsRequestAttributes(): void
    {
        $requestStack = new RequestStack();
        $requestStack->{'push'}(Request::create('/admin/items/1', 'PATCH', server: [
            'REMOTE_ADDR' => '192.0.2.15',
            'HTTP_USER_AGENT' => 'Unit Test',
        ]));
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), []);

        (new CollectRequestListener($requestStack))->onChangeAddRequest($event);

        $attributes = $event->getEntry()->getAttributes();
        $this->assertSame('192.0.2.15', $attributes->get('request_ip'));
        $this->assertSame('Unit Test', $attributes->get('user_agent'));
        $this->assertSame('PATCH', $attributes->get('request_method'));
        $this->assertSame('/admin/items/1', $attributes->get('request_path'));
    }
}
