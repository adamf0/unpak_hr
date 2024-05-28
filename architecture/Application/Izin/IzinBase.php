<?php

namespace Architecture\Application\Izin;

use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisIzin;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;

trait IzinBase 
{
    public ?Dosen $dosen;
    public ?Pegawai $pegawai;
    public Date $tanggal_pengajuan;
    public $tujuan;
    public ?JenisIzin $jenis_izin=null;
    public $dokumen;
    public $status;
    public $catatan;
    public $pic;

    public function GetDosen(){
        return $this->dosen;
    }
    public function GetPegawai(){
        return $this->pegawai;
    }
    public function GetTanggalPengajuan(){
        return $this->tanggal_pengajuan;
    }
    public function GetTujuan(){
        return $this->tujuan;
    }
    public function GetJenisIzin(){
        return $this->jenis_izin;
    }
    public function GetDokumen(){
        return $this->dokumen;
    }
    public function GetCatatan(){
        return $this->catatan;
    }
    public function GetStatus(){
        return $this->status;
    }
    public function GetPIC(){
        return $this->pic;
    }
}