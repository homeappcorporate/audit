<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

class Audit
{
    private StorageInterface $storage;
    /** @var ActivityData[] */

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function hold(ActivityData $activity):void
    {
        $this->storage->send($activity);
    }

    public function sendDataToPersistenceStorage():void
    {
//        $this->storage->send(...$this->data);
        $this->data = [];
    }
}
