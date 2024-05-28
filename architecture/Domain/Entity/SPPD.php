<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\SPPD\SPPDBase;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Support\Collection;

class SPPD extends BaseEntity{
    use SPPDBase;
    public function __construct(
        $id=null,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisSPPD $jenis_sppd=null,
        Date $tanggal_berangkat,
        ?Date $tanggal_kembali=null,
        $tujuan,
        $keterangan,
        $status,
        $catatan=null,
        ?Collection $list_anggota=null,
    ){
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->jenis_sppd = $jenis_sppd;
        $this->tanggal_berangkat = $tanggal_berangkat;
        $this->tanggal_kembali = $tanggal_kembali;
        $this->tujuan = $tujuan;
        $this->keterangan = $keterangan;
        $this->status = $status;
        $this->catatan = $catatan;
        $this->list_anggota = $list_anggota;
    }
}