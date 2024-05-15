<?php

namespace Architecture\Domain\Enum;

enum TypeNotif
{
    case Error;
    case Delete;
    case Create;
    case Update;

    public function val()
    {
        return match ($this) {
            self::Delete => 'notif-delete',
            self::Create => 'notif-create',
            self::Update => 'notif-update',
            default      => 'notif-error',
        };
    }
}
