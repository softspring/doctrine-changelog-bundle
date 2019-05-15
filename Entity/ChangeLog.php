<?php

namespace Softspring\DoctrineChangeLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Softspring\DoctrineChangeLogBundle\Collector\Changes;

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
     * @param Changes $changes
     * @return ChangeLog
     */
    public static function create(Changes $changes)
    {
        $changeLog = new static();

        // set basic data
        $changeLog->setTimestamp($changes->getTimestamp());
        $changeLog->setEntityClass($changes->getEntityClass());
        $changeLog->setEntityId($changes->getEntityIdentifier());
        $changeLog->setChanges($changes->getChanges());

        // set action
        $changeLog->setAction($changes->getAttributes()->get('action'));

        // set username
        $changeLog->setUsername($changes->getAttributes()->get('username'));

        // set request data
        $changeLog->setIp($changes->getAttributes()->get('client_ip'));
        $changeLog->setUserAgent($changes->getAttributes()->get('user_agent'));
        $changeLog->setRequestPath($changes->getAttributes()->get('request_path'));
        $changeLog->setRequestMethod($changes->getAttributes()->get('request_method'));

        return $changeLog;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp(int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string|null
     */
    public function getEntityClass(): ?string
    {
        return $this->entityClass;
    }

    /**
     * @param string|null $entityClass
     */
    public function setEntityClass(?string $entityClass): void
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return array|null
     */
    public function getEntityId(): ?array
    {
        return $this->entityId;
    }

    /**
     * @param array|null $entityId
     */
    public function setEntityId(?array $entityId): void
    {
        $this->entityId = $entityId;
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param string|null $action
     */
    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getChanges(): array
    {
        return $this->changes;
    }

    /**
     * @param array $changes
     */
    public function setChanges(array $changes): void
    {
        $this->changes = $changes;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     */
    public function setIp(?string $ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @param string|null $userAgent
     */
    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return string|null
     */
    public function getRequestMethod(): ?string
    {
        return $this->requestMethod;
    }

    /**
     * @param string|null $requestMethod
     */
    public function setRequestMethod(?string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return string|null
     */
    public function getRequestPath(): ?string
    {
        return $this->requestPath;
    }

    /**
     * @param string|null $requestPath
     */
    public function setRequestPath(?string $requestPath): void
    {
        $this->requestPath = $requestPath;
    }
}