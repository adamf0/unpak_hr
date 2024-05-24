<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

use App\Rules\CutiDateUnique;

class UpdateCutiRuleReq{
    public static function create($nidn = null, $nip = null) { 
        return [
            "id"            => "required",
            "jenis_cuti"    => "required",
            "lama_cuti"     => "required",
            "tanggal_mulai" =>  [
                "required",
                new CutiDateUnique($nidn, $nip, 'menunggu')
            ],
            "tanggal_akhir" => [
                "required",
                "after_or_equal:tanggal_mulai",
                new CutiDateUnique($nidn, $nip, 'menunggu')
            ],
            "tujuan"        => "required",
            "dokumen"       => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10000",
        ]; 
    }
}