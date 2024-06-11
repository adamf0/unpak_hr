<?php

namespace Architecture\Application\SPPD\Delete;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\TypeData;

class DeleteAllSPPDLaporanFileCommand extends Command
{
    public function __construct(public $id_sppd,public $type, public TypeData $option = TypeData::Entity) {}

    public function GetIDSPPD(){
        return $this->id_sppd;
    }
    public function GetType(){
        return $this->type;
    }
}