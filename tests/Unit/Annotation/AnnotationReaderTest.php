<?php

namespace Softspring\DoctrineChangeLogBundle\Tests\Unit\Annotation;

use PHPUnit\Framework\TestCase;
use Softspring\DoctrineChangeLogBundle\Annotation\AnnotationReader;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\NonRegistrableEntity;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAnnotationEntity;
use Softspring\DoctrineChangeLogBundle\Tests\Fixtures\RegistrableAttributeEntity;

class AnnotationReaderTest extends TestCase
{
    public function testDetectsRegistrableAnnotationsAndAttributes(): void
    {
        $reader = new AnnotationReader();

        $this->assertTrue($reader->isRegistrable(new RegistrableAnnotationEntity()));
        $this->assertTrue($reader->isRegistrable(new RegistrableAttributeEntity()));
        $this->assertFalse($reader->isRegistrable(new NonRegistrableEntity()));
    }

    public function testDetectsIgnoredFieldsFromAnnotationsAndAttributes(): void
    {
        $reader = new AnnotationReader();

        $this->assertSame(['ignored' => true], $reader->getIgnoredFields(new RegistrableAnnotationEntity()));
        $this->assertSame(['ignored' => true], $reader->getIgnoredFields(new RegistrableAttributeEntity()));
        $this->assertSame([], $reader->getIgnoredFields(new NonRegistrableEntity()));
    }
}
