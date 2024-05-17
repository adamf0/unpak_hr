<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPresensi;

class PresensiReferensi extends IPresensi{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}