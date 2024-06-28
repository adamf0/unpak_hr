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
        $unit_kerja=null,
        $status=null
    ){
        $this->nidn = $nidn;
        $this->nip = $nip;
        $this->nama = $nama;
        $this->unit = $unit;
        $this->struktural = $struktural;
        $this->unit_kerja = $unit_kerja;
        $this->status = $status;
    }
}