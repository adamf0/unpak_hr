<?php

namespace Architecture\Application\Izin\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\Izin\IzinBase;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalIzinCommand extends Command
{
    use IdentityCommand,IzinBase;
    public function __construct($id,$status,$catatan=null,$pic=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->status = $status;
        $this->catatan = $catatan;
        $this->pic = $pic;
    }
}