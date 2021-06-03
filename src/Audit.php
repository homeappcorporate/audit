<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

class Audit
{
    private StorageInterface $storage;
    /** @var ActivityData[] */
    private array $data = [];

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function save(ActivityData $activity):void
    {
        $this->data[] = $activity;
    }

    public function sendDataToPersistenceStorage():void
    {
        $this->storage->send($this->data);
        $this->data = [];
    }
}