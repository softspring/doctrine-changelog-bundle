<?php

namespace Softspring\DoctrineChangeLogBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;
use ReflectionClass;
use ReflectionException;
use Softspring\DoctrineChangeLogBundle\Mapping\Ignored as IgnoredMapping;
use Softspring\DoctrineChangeLogBundle\Mapping\Registrable as RegistrableMapping;

class AnnotationReader
{
    protected DoctrineAnnotationReader $reader;

    public function __construct()
    {
        $this->reader = new DoctrineAnnotationReader();
    }

    public function isRegistrable(object $entity): bool
    {
        try {
            $reflection = new ReflectionClass($entity);

            if ([] !== $reflection->getAttributes(RegistrableMapping::class)) {
                return true;
            }

            return (bool) $this->reader->getClassAnnotation($reflection, RegistrableMapping::class);
        } catch (ReflectionException $e) {
            return false;
        }
    }

    public function getIgnoredFields(object $entity): array
    {
        try {
            $reflection = new ReflectionClass(get_class($entity));

            $ignoredFields = [];

            foreach ($reflection->getProperties() as $property) {
                if ([] === $property->getAttributes(IgnoredMapping::class) && !$this->reader->getPropertyAnnotation($property, IgnoredMapping::class)) {
                    continue;
                }

                $ignoredFields[$property->getName()] = true;
            }

            return $ignoredFields;
        } catch (ReflectionException $e) {
            return [];
        }
    }
}
