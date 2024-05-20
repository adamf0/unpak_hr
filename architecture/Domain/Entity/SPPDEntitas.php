<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\ISPPD;

class SPPDEntitas extends ISPPD{
    public static function make(
        $id=null,
        $nidn=null,
        $nip=null,
        $jenis_sppd=null,
        $tanggal_berangkat=null,
        $tanggal_kembali=null,
        $tujuan=null,
        $keterangan=null,
        $status=null,
        $catatan=null,
    ){
        $instance = new self();
        $instance->id = $id;
        $instance->nidn = $nidn;
        $instance->nip = $nip;
        $instance->jenis_sppd = $jenis_sppd;
        $instance->tanggal_berangkat = $tanggal_berangkat;
        $instance->tanggal_kembali = $tanggal_kembali;
        $instance->tujuan = $tujuan;
        $instance->keterangan = $keterangan;
        $instance->status = $status;
        $instance->catatan = $catatan;
        return $instance;
    }
}