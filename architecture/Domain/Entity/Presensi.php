<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Presensi\PresensiBase;
use Architecture\Domain\ValueObject\Date;

class Presensi extends BaseEntity{
    use PresensiBase;
    
    public function __construct(
        $id=null,
        $nidn=null,
        $nip=null,
        ?Date $tanggal=null,
        ?Date $absen_masuk=null,
        ?Date $absen_keluar=null,
        $catatan_telat=null,
        $catatan_pulang=null,
        $otomatis_keluar=null
    ){
        $this->id = $id;
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->tanggal = $tanggal;
        $this->absen_masuk = $absen_masuk;
        $this->absen_keluar = $absen_keluar;
        $this->catatan_telat = $catatan_telat;
        $this->catatan_pulang = $catatan_pulang;
        $this->otomatis_keluar = $otomatis_keluar; 
    }
}