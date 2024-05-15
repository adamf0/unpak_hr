<?php
namespace Architecture\Shared\Behavioral;

use Architecture\Domain\Enum\TypeStatusPengajuan;
use Exception;

class FilterInternalByLevel implements FilterStartegy{
    public function __construct(public $param1, public $param2){}

    public function onFilter($row){
        if(is_null($this->param2)) throw new Exception("param2 is required");
        return in_array($this->param1, $this->param2)? $row->status!=TypeStatusPengajuan::DEFAULT->val() : $row;
    }
}