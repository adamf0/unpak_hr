<?php

namespace Architecture\Application\Pengguna\List;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\PagingQuery;
use Architecture\Shared\TypeData;

class GetAllPenggunaQuery extends Query
{
    use PagingQuery;
    public function __construct(
        public TypeData $option = TypeData::Entity
    ) {
        return $this;
    }
}