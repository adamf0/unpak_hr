<?php

namespace Architecture\Domain\RuleValidationRequest\JenisCuti;

class UpdateJenisCutiRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "nama"          => "required",
            "min"           => "required",
            "max"           => "required",
            "dokumen"       => "required",
        ]; 
    }
}