<?php

namespace Softspring\DoctrineChangeLogBundle\Metadata;

use Doctrine\Common\Annotations\AnnotationReader;
use Softspring\DoctrineChangeLogBundle\Annotation\Ignored;
use Softspring\DoctrineChangeLogBundle\Annotation\Registrable;

class MetadataReader
{
    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * MetadataReader constructor.
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct()
    {
        $this->reader = new AnnotationReader();
    }

    /**
     * @param object $entity
     * @return bool
     */
    public function isRegistrable(object $entity): bool
    {
        try {
            return (bool) $this->reader->getClassAnnotation(new \ReflectionClass(get_class($entity)), Registrable::class);
        } catch (\ReflectionException $e) {
            return false;
        }
    }

    /**
     * @param object $entity
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