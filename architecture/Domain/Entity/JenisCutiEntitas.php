<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IJenisCuti;

class JenisCutiEntitas extends IJenisCuti{
    public static function make($id=null,$nama=null,$min=null,$max=null,$dokumen=null,$kondisi=null){
        $instance = new self();
        $instance->id = $id;
        $instance->nama = $nama;
        $instance->min = $min;
        $instance->max = $max;
        $instance->dokumen = $dokumen;
        $instance->kondisi = $kondisi;

        return $instance;
    }
}