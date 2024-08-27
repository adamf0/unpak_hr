<?php

namespace Architecture\Application\Izin\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllIzinInRangeQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn=null,
        public $nip=null,
        public $dateRange=[],
        public TypeData $option = TypeData::Entity,
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
    public function GetNip(){
        return $this->nip;
    }
    public function GetDateRange(){
        return $this->dateRange;
    }
}