<?php
namespace Architecture\Domain\Entity;

class Prodi extends BaseEntity{
    public function __construct(
        public $id=null,
        public $namaProdi=null
    ){}

    public function GetNamaProdi(){
        return $this->namaProdi;
    }
}