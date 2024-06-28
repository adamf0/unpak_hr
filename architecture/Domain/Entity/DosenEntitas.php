<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IDosen;

class DosenEntitas extends IDosen{
    public static function make($nidn=null, $nama=null, ?Fakultas $fakultas=null, ?Prodi $prodi=null, $unit_kerja=null, $status=null){
        $instance = new self();
        $instance->nidn = $nidn;
        $instance->nama = $nama;
        $instance->fakultas = $fakultas;
        $instance->prodi = $prodi;
        $instance->unit_kerja = $unit_kerja;
        $instance->status = $status;
        return $instance;
    }
}