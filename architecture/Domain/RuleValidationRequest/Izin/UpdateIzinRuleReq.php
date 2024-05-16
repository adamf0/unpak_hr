<?php

namespace Architecture\Domain\RuleValidationRequest\Izin;

class UpdateIzinRuleReq{
    public static function create() { 
        return [
            "id"                => "required",
            "tanggal_pengajuan" => "required",
            "tujuan"            => "required",
            "jenis_izin"        => "required",
            "dokumen"           => "required",
            // "catatan"           => "required",
        ]; 
    }
}