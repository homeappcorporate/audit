<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-immutable
 */
class ActivityData
{
    private UuidInterface $requestId;
    private string $entityName;
    private string $entityId;
    private string $actionType;
    private ?int $actorId;
    private \DateTimeImmutable $createdAt;
    private ?string $ip;
    private ?array $changeSet;

    public function __construct(
        string $entityName,
        string $entityId,
        string $actionType,
        UuidInterface $requestId,
        ?int $actorId,
        ?string $ip,
        ?array $changeSet = []
    ) {
        $this->entityName = $entityName;
        $this->actionType = $actionType;
        $this->entityId = $entityId;
        $this->actorId = $actorId;
        $this->createdAt = new \DateTimeImmutable();
        $this->ip = $ip;
        $this->changeSet = $changeSet;
        $this->requestId = $requestId;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getActorId(): ?int
    {
        return $this->actorId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function getChangeSet(): ?array
    {
        return $this->changeSet;
    }

    public function getActionType(): string
    {
        return $this->actionType;
    }

    public function getRequestId(): UuidInterface
    {
        return $this->requestId;
    }
}
