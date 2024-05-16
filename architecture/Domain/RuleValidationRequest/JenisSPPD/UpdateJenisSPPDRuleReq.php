<?php

namespace Architecture\Domain\RuleValidationRequest\JenisSPPD;

class UpdateJenisSPPDRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "nama"          => "required",
        ]; 
    }
}