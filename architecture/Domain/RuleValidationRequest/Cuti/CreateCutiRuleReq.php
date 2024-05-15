<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

class CreateCutiRuleReq{
    public static function create() { 
        return [
            "jenis_cuti"    => "required",
            "lama_cuti"     => "required",
            "tanggal_mulai" => "required",
            "tanggal_akhir" => "required",
            "tujuan"        => "required",
            // "dokumen"       => "required",
        ]; 
    }
}