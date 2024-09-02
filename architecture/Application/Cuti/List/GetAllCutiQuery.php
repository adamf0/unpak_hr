<?php

namespace Architecture\Application\Cuti\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllCutiQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $verifikasi=0,
        public $tahun=null,
        public TypeData $option = TypeData::Entity,
        public $semua=true,
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNip(){
        return $this->nip;
    }
    public function IsVerificator(){
        return $this->verifikasi;
    }
    public function GetTahun(){
        return $this->tahun;
    }
    public function GetSemua(){
        return $this->semua;
    }
}