<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IFakultas;

class FakultasEntitas extends IFakultas{
    public static function make($id=null,$namaFakultas=null){
        $instance = new self();
        $instance->id = $id;
        $instance->namaFakultas = $namaFakultas;
        return $instance;
    }
}