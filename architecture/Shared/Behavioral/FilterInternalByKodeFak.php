<?php
namespace Architecture\Shared\Behavioral;

class FilterInternalByKodeFak implements FilterStartegy{
    public function __construct(public $param){}

    public function onFilter($row){
        return $row->Dosen?->kode_fak==$this->param;
    }
}