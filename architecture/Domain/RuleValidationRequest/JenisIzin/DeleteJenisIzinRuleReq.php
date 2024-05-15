<?php

namespace Architecture\Domain\RuleValidationRequest\JenisIzin;

class DeleteJenisIzinRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}