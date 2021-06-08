<?php

declare(strict_types=1);

namespace Test\Infra\Entities;

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

    public function __construct(int $id, string $login)
    {
        $this->id = $id;
        $this->login = $login;
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
}
