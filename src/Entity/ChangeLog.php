<?php

namespace Softspring\DoctrineChangeLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\DoctrineChangeLogBundle\Collector\ChangeEntry;

/**
 * @ORM\MappedSuperclass()
 * @ORM\Table(name="change_log", indexes={
 *      @ORM\Index(name="timestamp_idx", columns={"timestamp"})
 * })
 */
class ChangeLog
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(name="id", type="bigint", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column(name="timestamp", type="integer", options={"unsigned":true})
     */
    protected $timestamp;

    /**
     * @var string|null
     * @ORM\Column(name="username", type="string", nullable=true)
     */
    protected $username;

    /**
     * @var string|null
     * @ORM\Column(name="request_ip", type="string", nullable=true)
     */
    protected $ip;

    /**
     * @var string|null
     * @ORM\Column(name="user_agent", type="string", nullable=true)
     */
    protected $userAgent;

    /**
     * @var string|null
     * @ORM\Column(name="request_method", type="string", nullable=true)
     */
    protected $requestMethod;

    /**
     * @var string|null
     * @ORM\Column(name="request_path", type="string", nullable=true)
     */
    protected $requestPath;

    /**
     * @var string|null
     * @ORM\Column(name="entity_class", type="string", nullable=false)
     */
    protected $entityClass;

    /**
     * @var array|null
     * @ORM\Column(name="entity_id", type="json_array", nullable=false)
     */
    protected $entityId;

    /**
     * @var string|null
     * @ORM\Column(name="action", type="string", nullable=true)
     */
    protected $action;

    /**
     * @var array
     * @ORM\Column(name="changes", type="json_array", nullable=false)
     */
    protected $changes;

    /**
     * @return ChangeLog
     */
    public static function create(ChangeEntry $entry)
    {
        $changeLog = new static();

        // set basic data
        $changeLog->setTimestamp($entry->getTimestamp());
        $changeLog->setEntityClass($entry->getEntityClass());
        $changeLog->setEntityId($entry->getEntityIdentifier());
        $changeLog->setChanges($entry->getChanges());

        // set action
        $changeLog->setAction($entry->getAttributes()->get('action'));

        // set username
        $changeLog->setUsername($entry->getAttributes()->get('username'));

        // set request data
        $changeLog->setIp($entry->getAttributes()->get('client_ip'));
        $changeLog->setUserAgent($entry->getAttributes()->get('user_agent'));
        $changeLog->setRequestPath($entry->getAttributes()->get('request_path'));
        $changeLog->setRequestMethod($entry->getAttributes()->get('request_method'));

        return $changeLog;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    public function setEntityClass(?string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    public function getEntityId(): ?array
    {
        return $this->entityId;
    }

    public function setEntityId(?array $entityId): void
    {
        $this->entityId = $entityId;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    public function getChanges(): array
    {
        return $this->changes;
    }

    public function setChanges(array $changes): void
    {
        $this->changes = $changes;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getRequestMethod(): ?string
    {
        return $this->requestMethod;
    }

    public function setRequestMethod(?string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    public function getRequestPath(): ?string
    {
        return $this->requestPath;
    }

    public function setRequestPath(?string $requestPath): void
    {
        $this->requestPath = $requestPath;
    }
}
