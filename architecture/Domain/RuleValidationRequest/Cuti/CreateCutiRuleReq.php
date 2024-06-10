<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

use App\Rules\CutiDateUnique;

class CreateCutiRuleReq
{
    public static function create($nidn = null, $nip = null)
    {
        return [
            "jenis_cuti"    => "required",
            "lama_cuti"     => "required",
            "tanggal_mulai" =>  [
                "required",
                new CutiDateUnique($nidn, $nip)
            ],
            "tanggal_akhir" => [
                "required",
                "after_or_equal:tanggal_mulai",
                new CutiDateUnique($nidn, $nip)
            ],
            "tujuan"        => "required",
            "dokumen"       => "nullable|file|mimes:pdf,jpg,jpeg,png|max:10000",
            "verifikasi"    => "required",
        ];
    }
}
