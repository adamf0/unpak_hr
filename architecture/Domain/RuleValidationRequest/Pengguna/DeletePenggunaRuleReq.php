<?php

namespace Architecture\Domain\RuleValidationRequest\Pengguna;

class DeletePenggunaRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}