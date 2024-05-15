<?php

namespace Architecture\Domain\Enum;

enum TypeStatusPengajuanInsentif
{
    case Diteruskan;
    case Ditolak;
    case Default;

    public static function parse($value)
    {
        if(is_null($value)) return self::Default;

        return match ((int) $value) {
            1 => self::Diteruskan,
            0 => self::Ditolak
        };
    }
    public function val()
    {
        return match ($this) {
            self::Diteruskan   => '1',
            self::Ditolak      => '0',
            default             => null,
        };
    }
    public function toString()
    {
        return match ($this) {
            self::Diteruskan   => 'Diteruskan',
            self::Ditolak      => 'Ditolak',
            default             => null,
        };
    }
}
