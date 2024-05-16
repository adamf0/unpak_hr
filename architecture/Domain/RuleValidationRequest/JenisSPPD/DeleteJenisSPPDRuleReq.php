<?php

namespace Architecture\Domain\RuleValidationRequest\JenisSPPD;

class DeleteJenisSPPDRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}