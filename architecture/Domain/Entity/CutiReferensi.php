<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\ICuti;

class CutiReferensi extends ICuti{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}