<?php

namespace Architecture\Application\Presensi\FirstData;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class GetPresensiByNIDNQuery extends Query
{
    use IdentityCommand;
    public function __construct(
        public $nidn,
        public TypeData $option = TypeData::Entity,
    ) {}

    public function GetNIDN(){
        return $this->nidn;
    }
}