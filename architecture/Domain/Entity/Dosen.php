<?php
namespace Architecture\Domain\Entity;

use Architecture\Domain\Enum\TypeStatusAnggota;
use Architecture\Domain\Shared\NamingEntity;

class Dosen{
    use NamingEntity;
    public function __construct(
        public $nidn=null, 
        public $nama=null, 
        public ?Fakultas $fakultas=null, 
        public $kodeJurusan=null, 
        public ?Prodi $prodi=null, 
        public $nomorRekening=null,
        public ?TypeStatusAnggota $status=null,
    ){}

    public function GetNidn(){
        return $this->nidn;
    }
    public function GetFakultas(){
        return $this->fakultas;
    }
    public function GetKodeJurusan(){
        return $this->kodeJurusan;
    }
    public function GetProdi(){
        return $this->prodi;
    }
    public function GetNomorRekening(){
        return $this->nomorRekening;
    }
    public function GetStatus(){
        return $this->status;
    }
}