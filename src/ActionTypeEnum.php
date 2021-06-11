<?php

declare(strict_types=1);

namespace Homeapp\AuditBundle;

class ActionTypeEnum
{
    public const
        CREATE = 'create',
        INSERT = 'insert',
        DELETE = 'delete',
        UPDATE = 'update'
    ;

    public const NAMES = [
        self::CREATE,
        self::UPDATE,
    ];
}
