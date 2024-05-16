<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IIzin;

class IzinReferensi extends IIzin{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}