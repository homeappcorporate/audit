<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

class Auditable
{
    private array $classMap;

    public function __construct(array $classMap)
    {
        $this->classMap = array_flip($classMap);
    }

    public function isAuditable(object $entity): bool
    {
        $class = get_class($entity);
        return array_key_exists($class, $this->classMap);
    }
}
