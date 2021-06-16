<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

class ActionTypeEnum
{
    public const
        INSERT = 'insert',
        UPDATE = 'update',
        DELETE = 'delete';

    public const NAMES = [
        self::INSERT,
        self::UPDATE,
        self::DELETE,
    ];
}
