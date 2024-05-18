<?php

namespace Architecture\Domain\RuleValidationRequest\Presensi;

class CreatePresensiRuleReq{
    public static function create() { 
        return [
            "type"           => "required",
            "nidn"           => "required_if:nip,null",
            "nip"            => "required_if:nidn,null",
            "tanggal"        => "required",
            "absen_masuk"    => "required_if:type,absen_masuk",
            "absen_keluar"   => "required_if:type,absen_keluar",
            // "catatan_telat"  => "required",
        ]; 
    }
}