<?php

namespace Architecture\Domain\RuleValidationRequest\Pengguna;

class UpdatePenggunaRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
            "username" => "required",
            // "password" => "required",
            "nama" => "required",
            "level" => "required",
        ]; 
    }
}