<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPengguna;

class PenggunaEntitasTanpaStatus extends IPengguna{
    public static function make($id=null, $username=null, $password=null, $nama=null, $faculty=null, $level=null){
        $instance = new self();
        $instance->id = $id;
        $instance->username = $username;
        $instance->password = $password;
        $instance->nama = $nama;
        $instance->faculty = $faculty;
        $instance->level = $level;

        return $instance;
    }
}