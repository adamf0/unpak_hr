<?php

namespace Architecture\Application\Pegawai\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllPegawaiV2Query extends Query
{
    use PagingQuery;
    public function __construct(
        public $struktural=null,
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }

    public function GetStruktural(){
        return $this->struktural;
    }
}