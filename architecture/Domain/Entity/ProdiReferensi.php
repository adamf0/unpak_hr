<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IProdi;

class ProdiReferensi extends IProdi{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}