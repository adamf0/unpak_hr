<?php

namespace Architecture\Domain\Enum;

enum TypeRole
{
    case Default;
    case SDM;
    case WAREK;
    case KEUANGAN;
    case BAUM;
    case ADMIN;
    case DOSEN;
    case PEGAWAI;

    public function val()
    {
        return match ($this) {
            self::SDM => 'sdm',
            self::WAREK => 'warek',
            self::KEUANGAN => 'keuangan',
            self::BAUM => 'baum',
            self::ADMIN => 'admin',
            self::DOSEN => 'dosen',
            self::PEGAWAI => 'pegawai',
            default      => '',
        };
    }

    public static function parse($value)
    {
        if(is_null($value)) return self::Default;

        return match (strtolower($value)) {
            'sdm' => self::SDM,
            'warek' => self::WAREK,
            'keuangan' => self::KEUANGAN,
            'baum' => self::BAUM,
            'admin' => self::ADMIN,
            'dosen' => self::DOSEN,
            'pegawai' => self::PEGAWAI,
        };
    }
}
