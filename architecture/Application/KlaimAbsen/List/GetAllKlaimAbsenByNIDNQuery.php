<?php

namespace Architecture\Application\KlaimAbsen\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllKlaimAbsenByNIDNQuery extends Query
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