<?php

namespace Architecture\Domain\RuleValidationRequest\JenisCuti;

class DeleteJenisCutiRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}