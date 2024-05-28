<?php

namespace Architecture\Application\Dosen;

use Architecture\Domain\Entity\Fakultas;
use Architecture\Domain\Entity\Prodi;

trait DosenBase 
{
    public $nidn = null;
    public $nama = null;   
    public ?Fakultas $fakultas = null;
    public ?Prodi $prodi=null;

    public function GetNidn(){
        return $this->nidn;
    }
    public function GetNama(){
        return $this->nama;
    }
    public function GetFakultas(){
        return $this->fakultas;
    }
    public function GetProdi(){
        return $this->prodi;
    }
}