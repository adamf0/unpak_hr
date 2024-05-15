<?php

namespace Architecture\Application\Dosen;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\TypeData;

class CheckDosenByNIDNQuery extends Query
{
    public function __construct(
        public $nidn,
        public TypeData $option = TypeData::Entity,
    ) {}

    public function GetNIDN(){
        return $this->nidn;
    }
}