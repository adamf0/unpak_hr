<?php

namespace Architecture\Application\SPPD\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllSPPDQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $tahun=null,
        public $verifikasi=0,
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