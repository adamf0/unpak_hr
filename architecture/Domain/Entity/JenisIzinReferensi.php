<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IJenisIzin;

class JenisIzinReferensi extends IJenisIzin{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}