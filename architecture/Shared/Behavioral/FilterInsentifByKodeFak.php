<?php
namespace Architecture\Shared\Behavioral;

class FilterInsentifByKodeFak implements FilterStartegy{
    public function __construct(public $param){}

    public function onFilter($row){
        return $row->Dosen?->kode_fak==$this->param;
    }
}