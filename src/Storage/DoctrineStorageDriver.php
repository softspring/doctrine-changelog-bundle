<?php

namespace Softspring\DoctrineChangeLogBundle\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;
use Softspring\DoctrineChangeLogBundle\Collector\ChangesStack;
use Softspring\DoctrineChangeLogBundle\Entity\ChangeLog;

class DoctrineStorageDriver implements StorageDriverInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $changeLogClass;

    /**
     * DoctrineStorageDriver constructor.
     * @param EntityManagerInterface $em
     * @param string $changeLogClass
     */
    public function __construct(EntityManagerInterface $em, string $changeLogClass = ChangeLog::class)
    {
        $this->em = $em;
        $this->changeLogClass = $changeLogClass;
    }

    public function save(ChangeEntry $entry): void
    {
        $this->em->persist(ChangeLog::create($entry));
        $this->em->flush();
    }

    public function saveStack(ChangesStack $changesStack): void
    {
        while ($changes = $changesStack->pop()) {
            $this->em->persist(call_user_func([$this->changeLogClass, 'create'], $changes));
        }

        $this->em->flush();
    }
}