<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IDosen;

class DosenEntitas extends IDosen{
    public static function make($nidn=null, $nama=null, ?Fakultas $fakultas=null, ?Prodi $prodi=null){
        $instance = new self();
        $instance->nidn = $nidn;
        $instance->nama = $nama;
        $instance->fakultas = $fakultas;
        $instance->prodi = $prodi;
        return $instance;
    }
}