<?php

namespace Architecture\Application\SPPD\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class RejectSPPDCommand extends Command
{
    use IdentityCommand;
    public function __construct($id,public $catatan=null, public $pic=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->catatan = $catatan;
        $this->pic = $pic;
    }

    public function GetCatatan(){
        return $this->catatan;
    }
    public function GetPIC(){
        return $this->pic;
    }
}