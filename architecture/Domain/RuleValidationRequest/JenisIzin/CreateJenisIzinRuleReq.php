<?php

namespace Architecture\Domain\RuleValidationRequest\JenisIzin;

class CreateJenisIzinRuleReq{
    public static function create() { 
        return [
            "nama" => "required",
        ]; 
    }
}