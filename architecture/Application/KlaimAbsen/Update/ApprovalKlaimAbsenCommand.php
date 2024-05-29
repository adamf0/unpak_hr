<?php

namespace Architecture\Application\KlaimAbsen\Update;

use Architecture\Application\Abstractions\Messaging\Command;
use Architecture\Application\KlaimAbsen\KlaimAbsenBase;
use Architecture\Shared\IdentityCommand;
use Architecture\Shared\TypeData;

class ApprovalKlaimAbsenCommand extends Command
{
    use IdentityCommand,KlaimAbsenBase;
    public function __construct($id,$status,$catatan=null,$pic=null, public TypeData $option = TypeData::Entity) {
        $this->id = $id;
        $this->status = $status;
        $this->catatan = $catatan;
        $this->pic = $pic;
    }
}