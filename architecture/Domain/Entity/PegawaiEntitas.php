<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPegawai;

class PegawaiEntitas extends IPegawai{
    public static function make($nip=null, $nama=null, $unit=null){
        $instance = new self();
        $instance->nip = $nip;
        $instance->nama = $nama;
        $instance->unit = $unit;
        return $instance;
    }
}