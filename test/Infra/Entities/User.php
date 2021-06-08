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

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId() : int
    {
        return $this->id;
    }
}
