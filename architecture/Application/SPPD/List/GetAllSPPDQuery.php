<?php

namespace Architecture\Application\SPPD\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllSPPDQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }
}