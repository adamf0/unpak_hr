<?php

namespace Architecture\Application\Presensi;

use Architecture\Domain\ValueObject\Date;

trait PresensiBase 
{
    public $nidn=null;
    public $nip=null;
    public ?Date $tanggal=null;
    public ?Date $absen_masuk=null;
    public ?Date $absen_keluar=null;
    public $catatan_telat=null;
    public $catatan_pulang=null;
    public $otomatis_keluar=null;

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetTanggal(){
        return $this->tanggal;
    }
    public function GetAbsenMasuk(){
        return $this->absen_masuk;
    }
    public function GetAbsenKeluar(){
        return $this->absen_keluar;
    }
    public function GetCatatanTelat(){
        return $this->catatan_telat;
    }
    public function GetCatatanPulang(){
        return $this->catatan_pulang;
    }
    public function GetOtomatisKeluar(){
        return $this->otomatis_keluar;
    }
}