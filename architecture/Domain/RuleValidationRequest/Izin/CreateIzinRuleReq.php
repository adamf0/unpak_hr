<?php

namespace Architecture\Domain\RuleValidationRequest\Izin;

class CreateIzinRuleReq{
    public static function create() { 
        return [
            "tanggal_pengajuan" => "required",
            "tujuan"            => "required",
            "jenis_izin"        => "required",
            "dokumen"           => "required",
            // "catatan"           => "required",
        ]; 
    }
}