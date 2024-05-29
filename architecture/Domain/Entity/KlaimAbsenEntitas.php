<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IKlaimAbsen;

class KlaimAbsenEntitas extends IKlaimAbsen{
    public static function make($id=null,?Dosen $dosen=null, ?Pegawai $pegawai=null, ?Presensi $presensi=null,$jam_masuk=null,$jam_keluar=null,$tujuan=null, $dokumen=null, $catatan=null, $status=null){
        $instance = new self();
        $instance->id = $id;
        $instance->dosen = $dosen;
        $instance->pegawai = $pegawai;
        $instance->presensi = $presensi;
        $instance->jam_masuk = $jam_masuk;
        $instance->jam_keluar = $jam_keluar;
        $instance->tujuan = $tujuan;
        $instance->dokumen = $dokumen;
        $instance->status = $status;
        $instance->catatan = $catatan;
        return $instance;
    }
}