<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

interface ChangeSetInterface
{
    public function forCreate(object $entity): array;
}
