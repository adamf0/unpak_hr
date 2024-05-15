<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Contract\IDosen;
use Architecture\Domain\Enum\TypeStatusAnggota;

class DosenEntitasTanpaInfo extends IDosen{
    public static function make($nidn=null, $nama=null,?TypeStatusAnggota $status=null){
        $instance = new self();
        $instance->nidn = $nidn;
        $instance->nama = $nama;
        $instance->status = $status;
        return $instance;
    }
}