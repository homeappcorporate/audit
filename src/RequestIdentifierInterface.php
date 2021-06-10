<?php

namespace Homeapp\AuditBundle;

use Ramsey\Uuid\UuidInterface;

interface RequestIdentifierInterface
{
    public function getRequestId():UuidInterface;
}