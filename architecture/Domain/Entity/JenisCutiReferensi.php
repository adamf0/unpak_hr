<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IJenisCuti;

class JenisCutiReferensi extends IJenisCuti{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}