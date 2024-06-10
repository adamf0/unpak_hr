<?php

namespace Architecture\Application\KlaimAbsen;

use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\Entity\Presensi;

trait KlaimAbsenBase 
{
    public ?Dosen $dosen=null;
    public ?Pegawai $pegawai=null;
    public ?Presensi $presensi=null;
    public $jam_masuk=null;
    public $jam_keluar=null;
    public $tujuan=null;
    public $dokumen=null;
    public $status;
    public $catatan;
    
    public function GetDosen(){
        return $this->dosen;
    }
    public function GetPegawai(){
        return $this->pegawai;
    }
    public function GetPresensi(){
        return $this->presensi;
    }
    public function GetJamMasuk(){
        return $this->jam_masuk;
    }
    public function GetJamKeluar(){
        return $this->jam_keluar;
    }
    public function GetTujuan(){
        return $this->tujuan;
    }
    public function GetDokumen(){
        return $this->dokumen;
    }
    public function GetStatus(){
        return $this->status;
    }
    public function GetCatatan(){
        return $this->catatan;
    }
}