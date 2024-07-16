<?php

namespace Architecture\Application\Cuti\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllActiveCutiQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public $isnidn=null,
        public $isnip=null,
        public TypeData $option = TypeData::Entity,
    ) {
        return $this;
    }

    public function IsNIDN(){
        return $this->isnidn;
    }
    public function IsNip(){
        return $this->isnip;
    }
}