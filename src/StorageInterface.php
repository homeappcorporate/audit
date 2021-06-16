<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

interface StorageInterface
{

    /**
     * Send data to persistence storage
     */
    public function insert(ActivityData $d): void;
}
