<?php

namespace Architecture\Application\SPPD\Delete;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\TypeData;

class DeleteAllAnggotaSPPDCommand extends Command
{
    public function __construct(public $id_sppd, public TypeData $option = TypeData::Entity) {}

    public function GetIDSPPD(){
        return $this->id_sppd;
    }
}