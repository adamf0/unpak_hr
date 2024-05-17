<?php

namespace Architecture\Application\SPPD\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllSPPDByNIDNQuery extends Query
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