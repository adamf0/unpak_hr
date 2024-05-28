<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IProdi;

class ProdiEntitas extends IProdi{
    public static function make($id=null,$namaProdi=null){
        $instance = new self();
        $instance->id = $id;
        $instance->namaProdi = $namaProdi;
        return $instance;
    }
}