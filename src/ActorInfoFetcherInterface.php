<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

interface ActorInfoFetcherInterface
{
    public function getId(): ?int;

    public function getIp(): ?string;
}
