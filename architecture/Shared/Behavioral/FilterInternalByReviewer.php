<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class FilterInternalByReviewer implements FilterStartegy{
    public function __construct(public $param1){}

    public function onFilter($row){
        return ($row->ReviewSubstansi?->where('reviewerInternal',$this->param1)->count() || $row->ReviewSubstansi?->where('reviewerExternal',$this->param1)->count()) && TypeStatusPengajuan::parse($row->status)!=TypeStatusPengajuan::DEFAULT;
    }
}