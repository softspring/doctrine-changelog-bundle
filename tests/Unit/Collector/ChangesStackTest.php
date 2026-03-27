<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Collector;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;

class ChangesStackTest extends TestCase
{
    public function testPushPopAndPopAll(): void
    {
        $stack = new ChangesStack();
        $first = new ChangeEntry(['id' => 1], 'EntityA', []);
        $second = new ChangeEntry(['id' => 2], 'EntityB', []);

        $stack->push($first);
        $stack->push($second);

        $this->assertCount(2, $stack);
        $this->assertSame($second, $stack->pop());
        $this->assertCount(1, $stack);
        $this->assertSame([$first], $stack->popAll());
        $this->assertCount(0, $stack);
    }
}
