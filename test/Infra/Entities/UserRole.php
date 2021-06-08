<?php

declare(strict_types=1);

namespace Infra\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity();
 * @psalm-suppress UnusedClass
 */
class UserRole
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;
    private int $userId;
    private string $role;

    public function __construct(int $id, int $userId, string $role)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->role = $role;
    }

    public function getId() : int
    {
        return $this->id;
    }

    public function getUserId() : int
    {
        return $this->userId;
    }

    public function getRole() : string
    {
        return $this->role;
    }
}
