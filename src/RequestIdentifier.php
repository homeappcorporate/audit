<?php


namespace Homeapp\AuditBundle;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class RequestIdentifier implements RequestIdentifierInterface
{
    private ?UuidInterface $uuid = null;

    public function getRequestId(): UuidInterface
    {
        if (!$this->uuid) {
            $this->uuid = Uuid::uuid6();
        }
        return $this->uuid;
    }
}
