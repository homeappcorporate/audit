<?php

declare(strict_types=1);

namespace Test\Infra\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity();
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $id;
    /**
     * @ORM\Column(type="string")
     */
    private string $login;
    /**
     * @var Collection<UserRole>
     * @ORM\OneToMany(targetEntity="UserRole", mappedBy="user", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $roles;

    public function __construct(int $id, string $login)
    {
        $this->id = $id;
        $this->login = $login;
        $this->roles = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(UserRole $role)
    {
        $this->roles->add($role);
    }
}
