<?php

namespace Architecture\Domain\RuleValidationRequest\JenisIzin;

class UpdateJenisIzinRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "nama"          => "required",
        ]; 
    }
}