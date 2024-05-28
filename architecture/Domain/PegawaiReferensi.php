<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPegawai;

class PegawaiReferensi extends IPegawai{
    public static function make($nip=null){
        $instance = new self();
        $instance->nip = $nip;
        return $instance;
    }
}