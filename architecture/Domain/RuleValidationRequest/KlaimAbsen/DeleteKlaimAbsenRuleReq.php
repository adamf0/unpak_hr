<?php

namespace Architecture\Domain\RuleValidationRequest\KlaimAbsen;

class DeleteKlaimAbsenRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}