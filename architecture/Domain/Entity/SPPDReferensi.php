<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\ISPPD;

class SPPDReferensi extends ISPPD{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}