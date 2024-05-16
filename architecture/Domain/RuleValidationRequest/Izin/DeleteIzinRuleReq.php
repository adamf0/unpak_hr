<?php

namespace Architecture\Domain\RuleValidationRequest\Izin;

class DeleteIzinRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}