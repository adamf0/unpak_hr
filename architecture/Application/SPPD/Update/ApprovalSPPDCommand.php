<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct($id,public $pic=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->pic = $pic;
    }

    public function GetPIC(){
        return $this->pic;
    }
}