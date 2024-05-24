<?php

namespace Architecture\Domain\RuleValidationRequest\Pengguna;

class CreatePenggunaRuleReq{
    public static function create() { 
        return [
            "username" => "required",
            "password" => "required",
            "nama" => "required",
            "level" => "required",
        ]; 
    }
}