<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IJenisSPPD;

class JenisSPPDReferensi extends IJenisSPPD{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}