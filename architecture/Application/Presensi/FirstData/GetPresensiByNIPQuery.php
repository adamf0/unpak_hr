<?php

namespace Architecture\Application\Presensi\FirstData;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class GetPresensiByNIPQuery extends Query
{
    use IdentityCommand;
    public function __construct(
        public $nip,
        public TypeData $option = TypeData::Entity,
    ) {}

    public function GetNIP(){
        return $this->nip;
    }
}