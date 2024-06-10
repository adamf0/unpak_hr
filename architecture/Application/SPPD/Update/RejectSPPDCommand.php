<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class RejectSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct($id,public $catatan=null, public $status=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->catatan = $catatan;
        $this->status = $status;
    }

    public function GetCatatan(){
        return $this->catatan;
    }
    public function GetStatus(){
        return $this->status;
    }
}