<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IDosen;

class DosenReferensi extends IDosen{
    public static function make($nidn=null){
        $instance = new self();
        $instance->nidn = $nidn;
        return $instance;
    }
}