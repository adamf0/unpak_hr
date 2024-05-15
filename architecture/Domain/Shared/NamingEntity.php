<?php

namespace Architecture\Domain\Shared;

trait NamingEntity
{
    public $nama=null;
    
    public function GetNama(){
        return $this->nama;
    }
}
