<?php

namespace Architecture\Application\LaporanAbsen\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllLaporanAbsenQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $tanggal_mulai=null,
        public $tanggal_akhir=null,
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
    public function GetTanggalMulai(){
        return $this->tanggal_mulai;
    }
    public function GetTanggalAkhir(){
        return $this->tanggal_akhir;
    }
}