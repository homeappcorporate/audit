<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Homeapp\AuditBundle\Entity\Activity;

interface StorageInterface
{
    /**
     * Send data to persistence storage
     * @param ActivityData[]
     */
    public function send(array $data):void;
}
