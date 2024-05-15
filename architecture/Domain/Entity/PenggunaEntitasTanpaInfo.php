<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPengguna;

class PenggunaEntitasTanpaInfo extends IPengguna{
    public static function make($id=null, $nama=null, $level=null){
        $instance = new self();
        $instance->id = $id;
        $instance->nama = $nama;
        $instance->level = $level;

        return $instance;
    }
}