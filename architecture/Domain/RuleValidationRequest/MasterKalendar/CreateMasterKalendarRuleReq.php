<?php

namespace Architecture\Domain\RuleValidationRequest\MasterKalendar;

class CreateMasterKalendarRuleReq
{
    public static function create()
    {
        return [
            "tanggal_mulai" =>  "required",
            "tanggal_berakhir" => [
                "required",
                "after_or_equal:tanggal_mulai",
            ],
            "keterangan"    => "required",
        ];
    }
}
