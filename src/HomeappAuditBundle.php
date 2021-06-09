<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/** @psalm-suppress UnusedClass */
final class HomeappAuditBundle extends Bundle
{
    public function save(): string
    {
        return 'ok';
    }

    protected function getContainerExtensionClass()
    {
        return parent::getContainerExtensionClass();
    }
}
