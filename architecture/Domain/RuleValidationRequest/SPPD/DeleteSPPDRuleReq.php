<?php

namespace Architecture\Domain\RuleValidationRequest\SPPD;

class DeleteSPPDRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}