<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
// * @ORM\Entity()
 * @ORM\Table(name="homeapp_audit_activity")
 * @internal
 * @psalm-immutable
 */
class Activity
{
    public function __construct(
        string $entityName,
        string $actionType,
        string $entityId,
        ?int $actorId,
        \DateTimeImmutable $createdAt,
        ?string $ip,
        array $changeSet
    ) {
        $this->id = Uuid::uuid6()->toString();
        $this->entityName = $entityName;
        $this->actionType = $actionType;
        $this->entityId = $entityId;
        $this->actorId = $actorId;
        $this->createdAt = $createdAt;
        $this->ip = $ip;
        $this->changeSet = $changeSet;
    }

    /**
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private string $id;

    /**
     * Hope there will be no really long entity name
     * @ORM\Column(type="string", length=150)
     */
    private string $entityName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private string $actionType;

    /**
     * @ORM\Column(type="string", length=36)
     * Entity might have uuid as ID that is the reason to have here string with this length
     */
    private string $entityId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $actorId;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $createdAt;

    /**
     * @todo use ip format for storing
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $ip;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $changeSet;

    public function getId(): string
    {
        return $this->id;
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

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getChangeSet(): array
    {
        return $this->changeSet;
    }

    public function getActionType(): string
    {
        return $this->actionType;
    }
}
