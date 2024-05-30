<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPresensi;
use Architecture\Domain\ValueObject\Date;

class PresensiEntitas extends IPresensi{
    public static function make($id=null,?Dosen $dosen=null,?Pegawai $pegawai=null,?Date $tanggal=null,?Date $absen_masuk=null,?Date $absen_keluar=null,$catatan_telat=null,$catatan_pulang=null,$otomatis_keluar=null){
        $instance = new self();
        $instance->id=$id;
        $instance->dosen=$dosen;
        $instance->pegawai=$pegawai;
        $instance->tanggal=$tanggal;
        $instance->absen_masuk=$absen_masuk;
        $instance->absen_keluar=$absen_keluar;
        $instance->catatan_telat=$catatan_telat;
        $instance->catatan_pulang=$catatan_pulang;
        $instance->otomatis_keluar=$otomatis_keluar;

        return $instance;
    }
}