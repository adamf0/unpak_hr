<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct($id,public $status=null, public $file=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->status = $status;
        $this->file = $file;
    }

    public function GetStatus(){
        return $this->status;
    }
    public function GetFile(){
        return $this->file;
    }
}