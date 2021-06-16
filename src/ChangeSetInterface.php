<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

interface ChangeSetInterface
{
    public function get(object $entity): array;
}
