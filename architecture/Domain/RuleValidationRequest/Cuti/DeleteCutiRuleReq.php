<?php

namespace Architecture\Domain\RuleValidationRequest\Cuti;

class DeleteCutiRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}