<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\ISPPD;
use Architecture\Domain\ValueObject\Date;
use Illuminate\Support\Collection;

class SPPDEntitas extends ISPPD{
    public static function make(
        $id=null,
        ?Dosen $dosen=null,
        ?Pegawai $pegawai=null,
        ?JenisSPPD $jenis_sppd=null,
        ?Date $tanggal_berangkat=null,
        ?Date $tanggal_kembali=null,
        $tujuan=null,
        $keterangan=null,
        ?Pegawai $verifikasi=null,
        $status=null,
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
        $instance = new self();
        $instance->id = $id;
        $instance->dosen = $dosen;
        $instance->pegawai = $pegawai;
        $instance->jenis_sppd = $jenis_sppd;
        $instance->tanggal_berangkat = $tanggal_berangkat;
        $instance->tanggal_kembali = $tanggal_kembali;
        $instance->tujuan = $tujuan;
        $instance->keterangan = $keterangan;
        $instance->verifikasi = $verifikasi;
        $instance->status = $status;
        $instance->catatan = $catatan;
        $instance->dokumen_anggaran = $dokumen_anggaran;
        $instance->list_anggota = $list_anggota;
        $instance->intisari = $intisari;
        $instance->kontribusi = $kontribusi;
        $instance->rencana_tindak_lanjut = $rencana_tindak_lanjut;
        $instance->rencana_waktu_tindak_lanjut = $rencana_waktu_tindak_lanjut;
        $instance->foto_kegiatan = $foto_kegiatan;
        $instance->undangan = $undangan;
        return $instance;
    }
}