<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

use App\Rules\CutiDateUnique;
use Illuminate\Http\Request;

class CreateCutiRuleReq
{
    public static function create($nidn = null, $nip = null)
    {
        return [
            "jenis_cuti"    => "required",
            "lama_cuti"     => "required",
            "tanggal_mulai" =>  [
                "required",
                // new CutiDateUnique($nidn, $nip, 'menunggu')
            ],
            "tanggal_akhir" => "required",
            "tujuan"        => "required",
            // "dokumen"       => "required",
        ];
    }
}
