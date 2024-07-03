<?php

namespace Architecture\Domain\RuleValidationRequest\SPPD;

class CreateSPPDLaporanRuleReq{
    public static function create() { 
        return [
            "sppd"                          => "required|file|mimes:pdf|max:10000",
            "intisari"                      => "required",
            "kontribusi_ufu"                => "required",
            "rencana_tindak_lanjut"         => "required",
            "rencana_waktu_tindak_lanjut"   => "required|date",
        ]; 
    }

    public static function message() { 
        return [
            'kontribusi_ufu.required'  => 'Kontribusi pada Unit / Fakultas / Universitas field is required.',
            'rencana_tindak_lanjut.required'  => 'Rencana tindak lanjut field is required.',
            'rencana_waktu_tindak_lanjut.required'  => 'Rencana waktu pelaksanaan tindak lanjut field is required.',
        ];
    }
}