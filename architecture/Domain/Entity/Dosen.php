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
    ){
        $this->nidn = $nidn;
        $this->nama = $nama;
        $this->fakultas = $fakultas;
        $this->prodi = $prodi;
    }
}