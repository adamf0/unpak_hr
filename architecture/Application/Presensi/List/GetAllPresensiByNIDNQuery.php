<?php

namespace Architecture\Application\Presensi\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllPresensiByNIDNQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn,
        public $tahun_bulan,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetTahunBulan(){
        return $this->tahun_bulan;
    }
}