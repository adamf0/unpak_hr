<?php

namespace Architecture\Application\JenisSPPD\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllJenisSPPDQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }
}