<?php
namespace Architecture\Domain\Entity;

class Fakultas extends BaseEntity{
    public function __construct(public $id=null,public $namaFakultas=null){}

    public function GetNamaFakultas(){
        return $this->namaFakultas;
    }
}