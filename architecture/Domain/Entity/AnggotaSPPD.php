<?php
namespace Architecture\Domain\Entity;

class AnggotaSPPD extends BaseEntity{
    public function __construct(
        public $id=null,
        public $nidn=null,
        public $nip=null,
        public $nama=null
    ){}

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetNama(){
        return $this->nama;
    }
}