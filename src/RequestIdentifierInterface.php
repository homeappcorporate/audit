<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Ramsey\Uuid\UuidInterface;

interface RequestIdentifierInterface
{
    public function getRequestId(): UuidInterface;
}
