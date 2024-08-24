<?php

namespace Architecture\Application\SPPD;

use Architecture\Domain\Entity\Dosen;
use Architecture\Domain\Entity\JenisSPPD;
use Architecture\Domain\Entity\Pegawai;
use Architecture\Domain\ValueObject\Date;
use Architecture\Domain\ValueObject\File;
use Illuminate\Support\Collection;

trait SPPDBase 
{
    public ?Dosen $dosen=null;
    public ?Pegawai $pegawai=null;
    public ?JenisSPPD $jenis_sppd=null;
    public ?Date $tanggal_berangkat=null;
    public ?Date $tanggal_kembali=null;
    public $tujuan=null;
    public $keterangan=null;
    public $sarana_transportasi=null;
    public ?Pegawai $verifikasi=null;
    public $status=null;
    public $catatan=null;
    public $dokumen_anggaran=null;
    public ?Collection $list_anggota=null;
    public $intisari=null;
    public $kontribusi=null;
    public $rencana_tindak_lanjut=null;
    public $rencana_waktu_tindak_lanjut=null;
    public ?Collection $foto_kegiatan=null;
    public ?Collection $undangan=null;
    public ?File $file_sppd_laporan=null;

    public function GetFileSppdLaporan(){
        return $this->file_sppd_laporan;
    }
    public function GetIntisari(){
        return $this->intisari;
    }
    public function GetKontribusi(){
        return $this->kontribusi;
    }
    public function GetRencanaTindakLanjut(){
        return $this->rencana_tindak_lanjut;
    }
    public function GetRencanaWaktuTindakLanjut(){
        return $this->rencana_waktu_tindak_lanjut;
    }
    public function GetFotoKegiatan(){
        return $this->foto_kegiatan;
    }
    public function GetUndangan(){
        return $this->undangan;
    }
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
    public function GetSaranaTransportasi(){
        return $this->sarana_transportasi;
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
    public function GetVerifikasi(){
        return $this->verifikasi;
    }
}