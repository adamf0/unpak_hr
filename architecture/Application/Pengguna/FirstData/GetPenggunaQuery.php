<?php

namespace Architecture\Application\Pengguna\FirstData;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class GetPenggunaQuery extends Query
{
    use IdentityCommand;
    public function __construct(
        public $id,
        public TypeData $option = TypeData::Entity,
    ) {}
}