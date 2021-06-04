<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Doctrine\ORM\Event\PreFlushEventArgs;

class EventListener
{

    private Audit $audit;

    public function __construct(Audit $audit)
    {
        $this->audit = $audit;
    }

    public function preFlush(PreFlushEventArgs $args)
    {
        $this->audit->sendDataToPersistenceStorage();
    }
}
