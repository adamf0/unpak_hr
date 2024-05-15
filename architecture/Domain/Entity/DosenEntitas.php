<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IDosen;

class DosenEntitas extends IDosen{
    public static function make($nidn=null, $nama=null, ?Fakultas $fakultas=null, $kodeJurusan=null, ?Prodi $prodi=null, $nomorRekening=null){
        $instance = new self();
        $instance->nidn = $nidn;
        $instance->nama = $nama;
        $instance->fakultas = $fakultas;
        $instance->kodeJurusan = $kodeJurusan;
        $instance->prodi = $prodi;
        $instance->nomorRekening = $nomorRekening;
        return $instance;
    }
}