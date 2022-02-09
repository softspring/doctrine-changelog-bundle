<?php

namespace Softspring\DoctrineChangeLogBundle\Annotation;

use Doctrine\Common\Annotations\AnnotationReader as DoctrineAnnotationReader;

class AnnotationReader
{
    /**
     * @var DoctrineAnnotationReader
     */
    protected $reader;

    /**
     * MetadataReader constructor.
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $this->reader = new DoctrineAnnotationReader();
    }

    public function isRegistrable(object $entity): bool
    {
        try {
            return (bool) $this->reader->getClassAnnotation(new \ReflectionClass(get_class($entity)), Registrable::class);
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * @return string[]
     */
    public function getIgnoredFields(object $entity): array
    {
        try {
            $reflection = new \ReflectionClass(get_class($entity));

            $ignoredFields = [];

            foreach ($reflection->getProperties() as $property) {
                if (!$this->reader->getPropertyAnnotation($property, Ignored::class)) {
                    continue;
                }

                $ignoredFields[$property->getName()] = true;
            }

            return $ignoredFields;
        } catch (\ReflectionException $e) {
            return [];
        }
    }
}
