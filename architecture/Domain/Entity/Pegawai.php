<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Pegawai\PegawaiBase;

class Pegawai{
    use PegawaiBase;
    public function __construct(
        $nidn=null,
        $nip=null, 
        $nama=null, 
        $unit=null,
        $struktural=null,
        $status=null
    ){
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->nama = $nama;
        $this->unit = $unit;
        $this->struktural = $struktural;
        $this->status = $status;
    }
}