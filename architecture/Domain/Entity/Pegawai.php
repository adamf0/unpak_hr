<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Pegawai\PegawaiBase;

class Pegawai{
    use PegawaiBase;
    public function __construct(
        $nip=null, 
        $nama=null, 
        $unit=null, 
    ){
        $this->nip = $nip;
        $this->nama = $nama;
        $this->unit = $unit;
    }
}