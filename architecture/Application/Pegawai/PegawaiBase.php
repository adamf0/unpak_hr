<?php

namespace Architecture\Application\Pegawai;

trait PegawaiBase 
{
    public $nidn = null;
    public $nip = null;
    public $nama = null;   
    public $unit = null;
    public $struktural = null;
    public $unit_kerja = null;
    public $status = null;

    public function GetNidn(){
        return $this->nidn;
    }
    public function GetNip(){
        return $this->nip;
    }
    public function GetNama(){
        return $this->nama;
    }
    public function GetUnit(){
        return $this->unit;
    }
    public function GetStruktural(){
        return $this->struktural;
    }
    public function GetUnitKerja(){
        return $this->unit_kerja;
    }
    public function GetStatus(){
        return $this->status;
    }
}