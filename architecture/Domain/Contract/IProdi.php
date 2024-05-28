<?php
namespace Architecture\Domain\Contract;

use Architecture\Domain\Entity\BaseEntity;

abstract class IProdi extends BaseEntity{
    public $namaProdi=null;
    
    public function GetNamaProdi(){
        return $this->namaProdi;
    }  
}