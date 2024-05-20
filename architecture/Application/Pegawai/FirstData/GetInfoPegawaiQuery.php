<?php

namespace Architecture\Application\Pegawai\FirstData;

use Architecture\Application\Abstractions\Messaging\Query;
use Architecture\Shared\TypeData;

class GetInfoPegawaiQuery extends Query
{
    public function __construct(
        public $nip,
        public TypeData $option = TypeData::Entity,
    ) {}

    public function GetNIP(){
        return $this->nip;
    }
}