<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @internal
 * @psalm-immutable
 */
class Activity
{
    public function __construct(
        string $entityName,
        string $entityId,
        ?int $actorId,
        \DateTimeImmutable $createdAt,
        string $ip,
        array $changeSet
    ) {
        $this->id = Uuid::uuid6()->toString();
        $this->entityName = $entityName;
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
     * @ORM\Column(type="string")
     */
    private string $ip;

    /**
     * @ORM\Column(type="json")
     */
    private array $changeSet;

    /**
     * @return string
     */
    public function getId() : string
    {
        return $this->id;
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
