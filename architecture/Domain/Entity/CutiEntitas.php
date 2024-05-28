<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\ICuti;
use Architecture\Domain\ValueObject\Date;

class CutiEntitas extends ICuti{
    public static function make($id=null,?Dosen $dosen=null,?Pegawai $pegawai=null,?JenisCuti $jenis_cuti=null,$lama_cuti=null,Date $tanggal_mulai=null,?Date $tanggal_akhir=null,$tujuan=null,$dokumen=null,$catatan=null,$status=null){
        $instance = new self();
        $instance->id = $id;
        $instance->dosen = $dosen;
        $instance->pegawai = $pegawai;
        $instance->jenis_cuti = $jenis_cuti;
        $instance->lama_cuti = $lama_cuti;
        $instance->tanggal_mulai = $tanggal_mulai;
        $instance->tanggal_akhir = $tanggal_akhir;
        $instance->tujuan = $tujuan;
        $instance->dokumen = $dokumen;
        $instance->status = $status;
        $instance->catatan = $catatan;
        return $instance;
    }
}