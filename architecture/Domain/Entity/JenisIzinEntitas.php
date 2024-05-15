<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IJenisIzin;

class JenisIzinEntitas extends IJenisIzin{
    public static function make($id=null,$nama=null){
        $instance = new self();
        $instance->id = $id;
        $instance->nama = $nama;
        
        return $instance;
    }
}