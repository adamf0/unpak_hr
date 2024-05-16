<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IIzin;
use Architecture\Domain\ValueObject\Date;

class IzinEntitas extends IIzin{
    public static function make(
        $id=null,
        $nidn=null,
        $nip=null,
        Date $tanggal_pengajuan=null,
        $tujuan=null,
        ?JenisIzin $jenis_izin=null,
        $dokumen=null,
        $catatan=null,
        $status=null,
    ){
        $instance = new self();
        $instance->id = $id;
        $instance->nidn = $nidn;
        $instance->nip = $nip;
        $instance->tanggal_pengajuan = $tanggal_pengajuan;
        $instance->tujuan = $tujuan;
        $instance->jenis_izin = $jenis_izin;
        $instance->dokumen = $dokumen;
        $instance->catatan = $catatan;
        $instance->status = $status;
        
        return $instance;
    }
}