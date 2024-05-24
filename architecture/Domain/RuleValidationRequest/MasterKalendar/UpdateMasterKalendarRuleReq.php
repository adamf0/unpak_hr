<?php

namespace Architecture\Domain\RuleValidationRequest\MasterKalendar;

class UpdateMasterKalendarRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "tanggal_mulai" =>  "required",
            "tanggal_berakhir" => [
                "required",
                "after_or_equal:tanggal_mulai",
            ],
            "keterangan"    => "required",
        ]; 
    }
}