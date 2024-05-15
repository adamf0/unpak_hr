<?php

namespace Architecture\Domain\RuleValidationRequest\Rule;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Architecture\External\Persistance\ORM\User as ModelUser;

class CreateAddReviewerRuleReq{
    public static function create(Request $request,$referenceKey="id_penelitian_internal") { 
        $foundAccount = ModelUser::where('nidn_username',$request?->get('username'))->exists();

        return [
            "type"                      => "required",
            $referenceKey               => "required",
            "username"                  => "required_if:type,external",
            "password"                  => Rule::requiredIf($request?->get('type')=="external" && !$foundAccount),
            "name"                      => Rule::requiredIf($request?->get('type')=="external" && !$foundAccount),
            "nidn"                      => "required_if:type,internal",
        ]; 
    }
}