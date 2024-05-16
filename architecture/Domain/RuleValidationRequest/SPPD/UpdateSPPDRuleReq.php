<?php

namespace Architecture\Domain\RuleValidationRequest\SPPD;

class UpdateSPPDRuleReq{
    public static function create() { 
        return [
            "id"                => "required",
            "jenis_sppd"        => "required",
            "tanggal_berangkat" => "required",
            "tanggal_kembali"   => "required",
            "tujuan"            => "required",
            "keterangan"        => "required",
        ]; 
    }
}