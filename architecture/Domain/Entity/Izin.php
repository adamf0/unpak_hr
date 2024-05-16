<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Izin\IzinBase;
use Architecture\Domain\ValueObject\Date;

class Izin extends BaseEntity{
    use IzinBase;
    public function __construct(
        $id,
        $nidn,
        $nip,
        Date $tanggal_pengajuan,
        $tujuan,
        ?JenisIzin $jenis_izin=null,
        $dokumen,
        $catatan,
        $status,
    ){
        $this->id = $id;
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->tanggal_pengajuan = $tanggal_pengajuan;
        $this->tujuan = $tujuan;
        $this->jenis_izin = $jenis_izin;
        $this->dokumen = $dokumen;
        $this->catatan = $catatan;
        $this->status = $status;
    }
}