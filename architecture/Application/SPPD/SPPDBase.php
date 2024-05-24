<?php

namespace Architecture\Application\SPPD;

use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Support\Collection;

trait SPPDBase 
{
    public $nidn;
    public $nip;
    public ?JenisSPPD $jenis_sppd=null;
    public Date $tanggal_berangkat;
    public ?Date $tanggal_kembali=null;
    public $tujuan;
    public $keterangan;
    public $status;
    public $catatan=null;
    public ?Collection $list_anggota=null;

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetJenisSPPD(){
        return $this->jenis_sppd;
    }
    public function GetTanggalBerangkat(){
        return $this->tanggal_berangkat;
    }
    public function GetTanggalKembali(){
        return $this->tanggal_kembali;
    }
    public function GetTujuan(){
        return $this->tujuan;
    }
    public function GetKeterangan(){
        return $this->keterangan;
    }
    public function GetStatus(){
        return $this->status;
    }
    public function GetCatatan(){
        return $this->catatan;
    }
    public function GetListAnggota(){
        return $this->list_anggota;
    }
}