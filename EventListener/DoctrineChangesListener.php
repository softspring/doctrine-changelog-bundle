<?php

namespace Softspring\DoctrineChangeLogBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Softspring\DoctrineChangeLogBundle\Event\DeletionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\InsertionChangeEvent;
use Softspring\DoctrineChangeLogBundle\Event\UpdateChangeEvent;
use Softspring\DoctrineChangeLogBundle\Metadata\MetadataReader;
use Softspring\DoctrineChangeLogBundle\SfsDoctrineChangeLogEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DoctrineChangesListener implements EventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var MetadataReader
     */
    protected $metadataReader;

    /**
     * DoctrineChangesListener constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param MetadataReader $metadataReader
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, MetadataReader $metadataReader)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->metadataReader = $metadataReader;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow))) {
                continue;
            }

            $event = new InsertionChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes);
            $this->eventDispatcher->dispatch(SfsDoctrineChangeLogEvents::INSERTION, $event);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow))) {
                continue;
            }

            $event = new UpdateChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes);
            $this->eventDispatcher->dispatch(SfsDoctrineChangeLogEvents::UPDATE, $event);
        }

        foreach ($uow->getScheduledEntityDeletions() as $entityId => $entity) {
            if (!$this->metadataReader->isRegistrable($entity)) {
                continue;
            }

            if (empty($changes = $this->getChanges($entity, $uow))) {
                continue;
            }

            $event = new DeletionChangeEvent($uow->getEntityIdentifier($entity), $entity, $changes);
            $this->eventDispatcher->dispatch(SfsDoctrineChangeLogEvents::DELETION, $event);
        }

        // TODO process collections
        $colUpdates = $uow->getScheduledCollectionUpdates();
        $colDeletions = $uow->getScheduledCollectionDeletions();
    }

    /**
     * @param object $entity
     * @param UnitOfWork $uow
     * @return array
     */
    protected function getChanges(object $entity, UnitOfWork $uow): array
    {
        $changes = $uow->getEntityChangeSet($entity);
        $ignoredFields = $this->metadataReader->getIgnoredFields($entity);

        foreach (array_keys($ignoredFields) as $property) {
            if (isset($changes[$property])) {
                unset($changes[$property]);
            }
        }

        return $changes;
    }
}