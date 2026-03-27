<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\DependencyInjection\SfsDoctrineChangeLogExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SfsDoctrineChangeLogExtensionTest extends TestCase
{
    public function testLoadsCollectorsAndDoctrineStorage(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsDoctrineChangeLogExtension();

        $extension->load([[
            'collect' => [
                'request' => true,
                'user' => false,
                'action' => true,
            ],
            'storage' => [
                'enabled' => true,
                'driver' => 'doctrine',
            ],
        ]], $container);

        $this->assertTrue($container->hasDefinition('Softspring\DoctrineChangeLogBundle\EventListener\CollectRequestListener'));
        $this->assertFalse($container->hasDefinition('Softspring\DoctrineChangeLogBundle\EventListener\CollectUserListener'));
        $this->assertTrue($container->hasDefinition('Softspring\DoctrineChangeLogBundle\EventListener\CollectActionListener'));
        $this->assertTrue($container->hasAlias('Softspring\DoctrineChangeLogBundle\Storage\StorageDriverInterface'));
        $this->assertSame('doctrine', $container->getParameter('sfs_doctrine_changelog.storage.driver'));
    }

    public function testDoesNotLoadStorageWhenDisabled(): void
    {
        $container = new ContainerBuilder();
        $extension = new SfsDoctrineChangeLogExtension();

        $extension->load([[]], $container);

        $this->assertFalse($container->hasAlias('Softspring\DoctrineChangeLogBundle\Storage\StorageDriverInterface'));
    }
}
