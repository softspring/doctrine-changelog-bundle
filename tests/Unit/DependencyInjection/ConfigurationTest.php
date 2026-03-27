<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfig(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, [[]]);

        $this->assertSame([
            'collect' => [
                'request' => true,
                'user' => true,
                'action' => true,
            ],
            'storage' => [
                'enabled' => false,
            ],
        ], $config);
    }

    public function testDoctrineStorageConfig(): void
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $config = $processor->processConfiguration($configuration, [[
            'collect' => [
                'user' => false,
            ],
            'storage' => [
                'enabled' => true,
                'driver' => 'doctrine',
            ],
        ]]);

        $this->assertSame('doctrine', $config['storage']['driver']);
        $this->assertFalse($config['collect']['user']);
    }
}
