<?php

namespace Architecture\Domain\RuleValidationRequest\SPPD;

class CreateSPPDRuleReq{
    public static function create() { 
        return [
            "jenis_sppd"        => "required",
            "tanggal_berangkat" => "required",
            "tanggal_kembali"   => "required",
            "tujuan"            => "required",
            "keterangan"        => "required",
        ]; 
    }
}