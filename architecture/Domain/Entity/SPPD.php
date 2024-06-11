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
        ?Date $tanggal_berangkat=null,
        ?Date $tanggal_kembali=null,
        $tujuan,
        $keterangan,
        $status,
        $catatan=null,
        $dokumen_anggaran=null,
        ?Collection $list_anggota=null,
        $intisari=null,
        $kontribusi=null,
        $rencana_tindak_lanjut=null,
        $rencana_waktu_tindak_lanjut=null,
        ?Collection $foto_kegiatan=null,
        ?Collection $undangan=null,
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
        $this->dokumen_anggaran = $dokumen_anggaran;
        $this->list_anggota = $list_anggota;
        $this->intisari = $intisari;
        $this->kontribusi = $kontribusi;
        $this->rencana_tindak_lanjut = $rencana_tindak_lanjut;
        $this->rencana_waktu_tindak_lanjut = $rencana_waktu_tindak_lanjut;
        $this->foto_kegiatan = $foto_kegiatan;
        $this->undangan = $undangan;
    }
}