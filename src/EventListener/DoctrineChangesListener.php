<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Softspring\DoctrineChangeLogBundle\Annotation\AnnotationReader;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineChangesListener implements EventSubscriber
{
    protected EventDispatcherInterface $eventDispatcher;

    protected AnnotationReader $metadataReader;

    public function __construct(EventDispatcherInterface $eventDispatcher, AnnotationReader $metadataReader)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->metadataReader = $metadataReader;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    /**
     * @throws EntityNotFoundException
     */
    public function onFlush(OnFlushEventArgs $event): void
    {
        $em = $event->getObjectManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow, $em))) {
                continue;
            }

            $this->eventDispatcher->dispatch(new InsertionChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes));
        }

        foreach ($uow->getScheduledEntityUpdates() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow, $em))) {
                continue;
            }

            $this->eventDispatcher->dispatch(new UpdateChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes));
        }

        foreach ($uow->getScheduledEntityDeletions() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow, $em))) {
                continue;
            }

            $this->eventDispatcher->dispatch(new DeletionChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes));
        }

        // TODO process collections
        $colUpdates = $uow->getScheduledCollectionUpdates();
        $colDeletions = $uow->getScheduledCollectionDeletions();
    }

    protected function getChanges(object $entity, UnitOfWork $uow, EntityManagerInterface $em): array
    {
        $metadata = $em->getClassMetadata(get_class($entity));
        $changes = $uow->getEntityChangeSet($entity);
        $ignoredFields = $this->metadataReader->getIgnoredFields($entity);

        foreach (array_keys($ignoredFields) as $property) {
            if (isset($changes[$property])) {
                unset($changes[$property]);
            }
        }

        foreach ($changes as $field => [$old, $new]) {
            if ($metadata->hasAssociation($field)) {
                $association = $metadata->getAssociationMapping($field);
                $a = 1;
            } elseif ($metadata->hasField($field)) {
                if (isset($metadata->embeddedClasses[$field])) {
                    $embeddedMetadata = $metadata->embeddedClasses[$field];
                    $b = 1;
                } else {
                    $mapping = $metadata->getFieldMapping($field);
                    $a = 1;
                }
            } else {
                $no = 1;
                $a = 1;
            }
        }

        return $changes;
    }
}
