<?php

namespace Architecture\Application\Cuti;

use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisCuti;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;

trait CutiBase 
{
    public ?Dosen $dosen;
    public ?Pegawai $pegawai;
    public ?JenisCuti $jenis_cuti=null;
    public $lama_cuti;
    public Date $tanggal_mulai;
    public ?Date $tanggal_akhir=null;
    public $tujuan;
    public $dokumen;
    public ?Pegawai $verifikasi;
    public $status;
    public $catatan;

    public function GetDosen(){
        return $this->dosen;
    }
    public function GetPegawai(){
        return $this->pegawai;
    }
    public function GetJenisCuti(){
        return $this->jenis_cuti;
    }
    public function GetLamaCuti(){
        return $this->lama_cuti;
    }
    public function GetTanggalMulai(){
        return $this->tanggal_mulai;
    }
    public function GetTanggalAkhir(){
        return $this->tanggal_akhir;
    }
    public function GetTujuan(){
        return $this->tujuan;
    }
    public function GetDokumen(){
        return $this->dokumen;
    }
    public function GetVerifikasi(){
        return $this->verifikasi;
    }
    public function GetStatus(){
        return $this->status;
    }
    public function GetCatatan(){
        return $this->catatan;
    }
}