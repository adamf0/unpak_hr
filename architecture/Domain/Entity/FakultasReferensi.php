<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IFakultas;

class FakultasReferensi extends IFakultas{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}