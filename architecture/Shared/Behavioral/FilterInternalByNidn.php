<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;

class FilterInternalByNidn implements FilterStartegy{
    public function __construct(public $param1){}

    public function onFilter($row){
        return ($row->NIDN==$this->param1 || $row->AnggotaPeneliti->where("NIDN",$this->param1)->count());
    }
}