<?php

namespace Architecture\Application\Presensi\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllPresensiQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $tahun=null,
        public $tanggal_spesifik=null,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNIP(){
        return $this->nip;
    }
    public function GetTahun(){
        return $this->tahun;
    }
    public function GetTanggalSpesifik(){
        return $this->tanggal_spesifik;
    }
}