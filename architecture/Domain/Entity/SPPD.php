<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\ValueObject\Date;

class SPPD extends BaseEntity{
    use SPPDBase;
    public function __construct(
        $id=null,
        $nidn,
        $nip,
        ?JenisSPPD $jenis_sppd=null,
        Date $tanggal_berangkat,
        ?Date $tanggal_kembali=null,
        $tujuan,
        $keterangan,
        $status,
        $catatan=null,
    ){
        $this->id = $id;
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->jenis_sppd = $jenis_sppd;
        $this->tanggal_berangkat = $tanggal_berangkat;
        $this->tanggal_kembali = $tanggal_kembali;
        $this->tujuan = $tujuan;
        $this->keterangan = $keterangan;
        $this->status = $status;
        $this->catatan = $catatan;
    }
}