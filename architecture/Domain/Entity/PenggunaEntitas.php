<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPengguna;

class PenggunaEntitas extends IPengguna{
    public static function make($id=null, $nidn=null, $nip=null, $username=null, $password=null, $nama=null, $faculty=null, $programStudy=null, $position=null, $structural=null, $unit=null, $level=null, $active=true){
        $instance = new self();
        $instance->id = $id;
        $instance->nidn = $nidn;
        $instance->nip = $nip;
        $instance->username = $username;
        $instance->password = $password;
        $instance->nama = $nama;
        $instance->faculty = $faculty;
        $instance->programStudy = $programStudy;
        $instance->position = $position;
        $instance->level = $level;
        $instance->structural = $structural;
        $instance->unit = $unit;
        $instance->active = $active;

        return $instance;
    }
}