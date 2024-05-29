<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IKlaimAbsen;

class KlaimAbsenReferensi extends IKlaimAbsen{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}