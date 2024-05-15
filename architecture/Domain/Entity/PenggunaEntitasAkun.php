<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IPengguna;

class PenggunaEntitasAkun extends IPengguna{
    public static function make($username=null, $password=null){
        $instance = new self();
        $instance->username = $username;
        $instance->password = $password;

        return $instance;
    }
}