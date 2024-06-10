<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPegawai;

class PegawaiEntitas extends IPegawai{
    public static function make($nidn=null, $nip=null, $nama=null, $unit=null, $struktural=null){
        $instance = new self();
        $instance->nidn = $nidn;
        $instance->nip = $nip;
        $instance->nama = $nama;
        $instance->unit = $unit;
        $instance->struktural = $struktural;
        return $instance;
    }
}