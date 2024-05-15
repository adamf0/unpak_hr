<?php

namespace App\Rules;

use Architecture\External\Persistance\ORM\PenelitianInternal;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckSkema implements ValidationRule
{
    public function __construct(public $id_penelitian_internal=null,public $kategori_skema=[]){}
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $penelitianInternal = PenelitianInternal::select('id','id_skema')
                                                ->with(["KategoriSkema"=>fn($query)=>$query->select('id','nama')])
                                                ->find($this->id_penelitian_internal);
        if (in_array($penelitianInternal->KategoriSkema?->nama, $this->kategori_skema)) {
            $fail("The :attribute field is required");
        }
    }
}
