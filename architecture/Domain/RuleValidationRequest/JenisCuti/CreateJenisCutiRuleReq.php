<?php

namespace Architecture\Domain\RuleValidationRequest\JenisCuti;

class CreateJenisCutiRuleReq{
    public static function create() { 
        return [
            "nama"      => "required",
            "min"       => "required",
            "max"       => "required",
            "dokumen"   => "required",
        ]; 
    }
}