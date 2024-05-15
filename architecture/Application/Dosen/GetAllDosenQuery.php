<?php

namespace Architecture\Application\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\TypeData;

class GetAllDosenQuery extends Query
{
    public function __construct(
        public TypeData $option = TypeData::Entity,
    ) {}
}