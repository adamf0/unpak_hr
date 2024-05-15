<?php

namespace Architecture\Domain\Enum;

enum FormatDate
{
    case YMDHIS;
    case LDFYHIS;
    case LDFY;
    case DFYHIS;
    case DFY;
    case FY;
    case F;
    case Y;
    case Default;

    public function val()
    {
        return match ($this) {
            self::YMDHIS  => "Y-m-d H:i:s",
            self::LDFYHIS  => "l, d F Y H:i:s",
            self::LDFY  => "l, d F Y",
            self::DFYHIS   => "d F Y H:i:s",
            self::DFY      => "d F Y",
            self::FY       => "F Y",
            self::F        => "F",
            self::Y        => "Y",
            default        => "Y-m-d",
        };
    }
}
