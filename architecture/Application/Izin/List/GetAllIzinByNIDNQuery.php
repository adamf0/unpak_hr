<?php

namespace Architecture\Application\Izin\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllIzinByNIDNQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nidn,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetNIDN(){
        return $this->nidn;
    }
}