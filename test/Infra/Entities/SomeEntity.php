<?php

declare(strict_types=1);

namespace Test\Infra\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity();
 */
class SomeEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string")
     */
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId():string
    {
        return $this->id;
    }
}
