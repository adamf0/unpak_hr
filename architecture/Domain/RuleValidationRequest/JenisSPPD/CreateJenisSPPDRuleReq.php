<?php

namespace Architecture\Domain\RuleValidationRequest\JenisSPPD;

class CreateJenisSPPDRuleReq{
    public static function create() { 
        return [
            "nama" => "required",
        ]; 
    }
}