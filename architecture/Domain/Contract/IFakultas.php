<?php
namespace Architecture\Domain\Contract;

use Architecture\Domain\Entity\BaseEntity;

abstract class IFakultas extends BaseEntity{
    public $namaFakultas=null;
    
    public function GetNamaFakultas(){
        return $this->namaFakultas;
    }
}