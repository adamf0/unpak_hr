<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Cuti\CutiBase;
use Architecture\Domain\ValueObject\Date;

class Cuti extends BaseEntity{
    use CutiBase;
    public function __construct(
        $id,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisCuti $jenis_cuti=null,
        $lama_cuti,
        Date $tanggal_mulai,
        ?Date $tanggal_akhir=null,
        $tujuan,
        $dokumen,
        ?Pegawai $verifikasi=null,
        $catatan,
        $status
    ) {
        $this->id = $id;
        $this->dosen = $dosen;
        $this->pegawai = $pegawai;
        $this->jenis_cuti = $jenis_cuti;
        $this->lama_cuti = $lama_cuti;
        $this->tanggal_mulai = $tanggal_mulai;
        $this->tanggal_akhir = $tanggal_akhir;
        $this->tujuan = $tujuan;
        $this->dokumen = $dokumen;
        $this->verifikasi = $verifikasi;
        $this->status = $status;
        $this->catatan = $catatan;
    }
}