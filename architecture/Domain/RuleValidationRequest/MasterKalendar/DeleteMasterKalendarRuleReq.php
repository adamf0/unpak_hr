<?php

namespace Architecture\Domain\RuleValidationRequest\MasterKalendar;

class DeleteMasterKalendarRuleReq{
    public static function create() { 
        return [
            "id"            => "required",
        ]; 
    }
}