<?php

namespace Architecture\Application\Presensi\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllPresensiByNIPQuery extends Query
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