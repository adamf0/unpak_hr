<?php

namespace Architecture\Application\Pegawai;

trait PegawaiBase 
{
    public $nip = null;
    public $nama = null;   
    public $unit = null;

    public function GetNip(){
        return $this->nip;
    }
    public function GetNama(){
        return $this->nama;
    }
    public function GetUnit(){
        return $this->unit;
    }
}