<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct($id,public $status=null, public $file=null,public $sdm=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
    }

    public function GetStatus(){
        return $this->status;
    }
    public function GetFile(){
        return $this->file;
    }
    public function GetSDM(){
        return $this->sdm;
    }
}