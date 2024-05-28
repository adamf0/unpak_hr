<?php

namespace Architecture\Domain\RuleValidationRequest\Izin;

use App\Rules\IzinDateUnique;

class UpdateIzinRuleReq{
    public static function create($nidn = null, $nip = null) { 
        return [
            "id"                => "required",
            "tanggal_pengajuan" =>  [
                "required",
                new IzinDateUnique($nidn, $nip)
            ],
            "tujuan"            => "required",
            "jenis_izin"        => "required",
            // "dokumen"           => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10000",
            // "catatan"           => "required",
        ]; 
    }
}