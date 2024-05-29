<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\KlaimAbsen\KlaimAbsenBase;

class KlaimAbsen extends BaseEntity{
    use KlaimAbsenBase;
    public function __construct(
        $id=null,
        ?Dosen $dosen=null, 
        ?Pegawai $pegawai=null, 
        ?Presensi $presensi=null,
        $jam_masuk=null,
        $jam_keluar=null,
        $tujuan=null, 
        $dokumen=null,
        $catatan,
        $status
    ){
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->presensi = $presensi;
        $this->jam_masuk = $jam_masuk;
        $this->jam_keluar = $jam_keluar;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
        $this->status = $status;
        $this->catatan = $catatan;
    }
}