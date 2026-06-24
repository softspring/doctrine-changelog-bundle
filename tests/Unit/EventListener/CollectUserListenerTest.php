<?php

declare(strict_types=1);

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\EventListener;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\EventListener\CollectUserListener;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CollectUserListenerTest extends TestCase
{
    public function testSubscribesToChangeEvents(): void
    {
        $this->assertSame([
            InsertionChangeEvent::class => [['onChangeAddUsername', 98]],
            UpdateChangeEvent::class => [['onChangeAddUsername', 98]],
            DeletionChangeEvent::class => [['onChangeAddUsername', 98]],
        ], CollectUserListener::getSubscribedEvents());
    }

    public function testDoesNothingWithoutTokenStorage(): void
    {
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), []);

        (new CollectUserListener(null))->onChangeAddUsername($event);

        $this->assertSame([], $event->getEntry()->getAttributes()->all());
    }

    public function testDoesNothingWithoutToken(): void
    {
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), []);
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->expects($this->once())->method('getToken')->willReturn(null);

        (new CollectUserListener($storage))->onChangeAddUsername($event);

        $this->assertSame([], $event->getEntry()->getAttributes()->all());
    }

    public function testAddsUsernameFromToken(): void
    {
        $event = new UpdateChangeEvent(['id' => 1], new RegistrableAttributeEntity(), []);
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())->method('getUserIdentifier')->willReturn('admin@example.com');
        $storage = $this->createMock(TokenStorageInterface::class);
        $storage->expects($this->once())->method('getToken')->willReturn($token);

        (new CollectUserListener($storage))->onChangeAddUsername($event);

        $this->assertSame('admin@example.com', $event->getEntry()->getAttributes()->get('username'));
    }
}
