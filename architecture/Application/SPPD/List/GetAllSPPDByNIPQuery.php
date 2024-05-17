<?php

namespace Architecture\Application\SPPD\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllSPPDByNIPQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $nip,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetNIP(){
        return $this->nip;
    }
}