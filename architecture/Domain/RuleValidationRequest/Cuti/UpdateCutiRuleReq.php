<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

class UpdateCutiRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "jenis_cuti"    => "required",
            "lama_cuti"     => "required",
            "tanggal_mulai"  => "required",
            "tanggal_akhir" => "required",
            "tujuan"        => "required",
            // "dokumen"       => "required",
        ]; 
    }
}