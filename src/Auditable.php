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
        if ($class === 'App\Entity\Realty\Realty' || $class === 'App\Entity\Realty\Flat\Flat') {
            if (method_exists($entity, 'isMerged') && $entity->isMerged() === false) {
                return true; //только объекты Homeapp нужны в аудите
            } else {
                return false;
            }
        }
        
        return array_key_exists($class, $this->classMap);
    }
}
