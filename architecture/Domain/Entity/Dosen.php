<?php
namespace Architecture\Domain\Entity;

use Architecture\Application\Dosen\DosenBase;

class Dosen{
    use DosenBase;
    public function __construct(
        $nidn=null, 
        $nama=null, 
        ?Fakultas $fakultas=null, 
        ?Prodi $prodi=null,
        $unit_kerja=null,
        $status=null,
    ){
        $this->nidn = $nidn;
        $this->nama = $nama;
        $this->fakultas = $fakultas;
        $this->prodi = $prodi;
        $this->unit_kerja = $unit_kerja;
        $this->status = $status;
    }
}