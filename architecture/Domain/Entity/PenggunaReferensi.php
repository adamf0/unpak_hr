<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPengguna;

class PenggunaReferensi extends IPengguna{
    public static function make($id=null){
        $instance = new self();
        $instance->id = $id;
        return $instance;
    }
}