<?php

namespace Architecture\Application\SPPD\Create;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\TypeData;

class CreateSPPDLaporanFileCommand extends Command
{
    public function __construct(
        public $records=[],
        public TypeData $option = TypeData::Entity
    ) {}

    public function GetRecords(){
        return $this->records;
    }
}