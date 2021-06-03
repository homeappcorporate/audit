<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

/**
 * @psalm-immutable
 */
class ActivityData
{
    private string $entityName;
    private string $entityId;
    private ?int $actorId;
    private \DateTimeImmutable $createdAt;
    private string $ip;
    private array $changeSet;

    public function __construct(
        string $entityName,
        string $entityId,
        ?int $actorId,
        \DateTimeImmutable $createdAt,
        string $ip,
        array $changeSet
    ) {
        $this->entityName = $entityName;
        $this->entityId = $entityId;
        $this->actorId = $actorId;
        $this->createdAt = $createdAt;
        $this->ip = $ip;
        $this->changeSet = $changeSet;
    }

    /**
     * @return string
     */
    public function getEntityName() : string
    {
        return $this->entityName;
    }

    /**
     * @return string
     */
    public function getEntityId() : string
    {
        return $this->entityId;
    }

    /**
     * @return int|null
     */
    public function getActorId() : ?int
    {
        return $this->actorId;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt() : \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getIp() : string
    {
        return $this->ip;
    }

    /**
     * @return array
     */
    public function getChangeSet() : array
    {
        return $this->changeSet;
    }
}
