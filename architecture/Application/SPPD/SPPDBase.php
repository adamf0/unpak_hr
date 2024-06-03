<?php

namespace Architecture\Application\SPPD;

use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Support\Collection;

trait SPPDBase 
{
    public ?Dosen $dosen=null;
    public ?Pegawai $pegawai=null;
    public ?JenisSPPD $jenis_sppd=null;
    public ?Date $tanggal_berangkat=null;
    public ?Date $tanggal_kembali=null;
    public $tujuan;
    public $keterangan;
    public $status;
    public $catatan=null;
    public $dokumen_anggaran=null;
    public ?Collection $list_anggota=null;

    public function GetDosen(){
        return $this->dosen;
    }
    public function GetPegawai(){
        return $this->pegawai;
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
    public function GetDokumenAnggaran(){
        return $this->dokumen_anggaran;
    }
    public function GetListAnggota(){
        return $this->list_anggota;
    }
}